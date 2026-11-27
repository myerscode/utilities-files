<?php

namespace Myerscode\Utilities\Files;

use Myerscode\Utilities\Files\Exceptions\FileFormatException;
use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;
use Myerscode\Utilities\Files\Exceptions\InvalidFileTypeException;
use Myerscode\Utilities\Files\Exceptions\NotADirectoryException;
use Generator;
use InvalidArgumentException;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class Utility
 *
 * @package Myerscode\Utilities\Files
 */
class Utility
{
    private readonly Filesystem $filesystem;

    private readonly string $path;

    private readonly ?string $root;

    /**
     * @param string|null $root Optional base directory; reads (content, files, lines, namespace) and mutating
     *                          operations (delete, write, append, touch, makeDirectory) are refused for paths that resolve outside it.
     *
     * @throws InvalidArgumentException if the path uses a stream wrapper scheme other than file
     */
    public function __construct(string $path, ?string $root = null)
    {
        // a scheme of 2+ chars is a stream wrapper (phar://, php://, data:, http://); 1 char is a Windows drive
        if (preg_match('#^([a-z][a-z0-9+.\-]+):#i', $path, $m) && strtolower($m[1]) !== 'file') {
            throw new InvalidArgumentException(sprintf('Stream wrapper "%s" paths are not allowed.', $m[1]));
        }

        $this->root = $root;
        $this->path = $this->cleanPath($path);
        $this->filesystem = new Filesystem();
    }

    /**
     * Create a new instance of the files utility
     *
     * @param $path
     * @param string|null $root Optional base directory to confine reads and mutating operations to
     */
    public static function make(string $path, ?string $root = null): static
    {
        return new static($path, $root);
    }

    /**
     * Append content to the file
     */
    public function appendContent(string $content): Utility
    {
        return $this->putContent($content, FILE_APPEND);
    }

    /**
     * Get the assumed PRS4 class name for the file
     */
    public function className(): string
    {
        return $this->name();
    }

    /**
     * If the file exists get its content
     */
    public function content(): string
    {
        $this->assertWithinRoot();

        if ($this->isFile()) {
            $content = file_get_contents($this->path());

            if ($content === false) {
                throw new RuntimeException(sprintf('Could not read %s.', $this->path));
            }

            return $content;
        }

        throw new FileNotFoundException(sprintf('%s does not exist.', $this->path));
    }

    /**
     * Delete the file or directory with the given path
     */
    public function delete(): Utility
    {
        $this->assertWithinRoot();

        if ($this->filesystem->exists($this->path)) {
            $this->filesystem->remove($this->path);
        }

        return new static($this->path, $this->root);
    }

    /**
     * If the path is assumed to be a file, return its directory path
     * If the path is assumed to be a directory return its path
     */
    public function directory(): string
    {
        // an existing directory wins over the extension guess (e.g. v1.2, .git)
        if (!$this->isDirectory() && !in_array($this->extension(), ['', '0'], true)) {
            return rtrim(dirname($this->path), DIRECTORY_SEPARATOR);
        }

        return rtrim($this->path, DIRECTORY_SEPARATOR);
    }

    /**
     * Does the file or directory actually exists
     */
    public function exists(): bool
    {
        return $this->filesystem->exists($this->path);
    }

    /**
     * Guess the paths file extension
     */
    public function extension(bool $withDot = false): string
    {
        return implode('', [$withDot ? '.' : null, $this->fileInfo()->getExtension()]);
    }

    /**
     * Get a collection of spl file objects, from within the path if it's a directory
     */
    public function files(): array
    {
        $this->assertWithinRoot();

        if ($this->isDirectory()) {
            $fileList = iterator_to_array((new Finder())->files()->in($this->path)->getIterator(), false);

            usort($fileList, fn ($a, $b): int => strcmp($a->getRelativePathname(), $b->getRelativePathname()));

            return $fileList;
        }

        throw new NotADirectoryException();
    }

    /**
     * Get the fully qualified class name from the file (if it's a PHP file)
     *
     * @throws InvalidFileTypeException
     * @throws FileNotFoundException
     */
    public function fullyQualifiedClassname(): string
    {
        if ($this->exists()) {
            if (pathinfo($this->path, PATHINFO_EXTENSION) === 'php') {
                return $this->namespace() . '\\' . $this->className();
            }

            throw new InvalidFileTypeException(sprintf('%s is not a PHP file.', $this->path));
        }

        throw new FileNotFoundException(sprintf('%s does not exist.', $this->path));
    }

    public function hasAbsolutePath(): bool
    {
        return '/' === $this->path[0] || '\\' === $this->path[0] || strlen($this->path) > 3 && ctype_alpha($this->path[0]) && ':' === $this->path[1] && ('\\' === $this->path[2] || '/' === $this->path[2]) || null !== parse_url($this->path, PHP_URL_SCHEME);
    }

    /**
     * Is the path a directory
     */
    public function isDirectory(): bool
    {
        return is_dir($this->path);
    }

    /**
     * Is the path a file
     */
    public function isFile(): bool
    {
        return is_file($this->path);
    }

    /**
     * Stream the file line by line without loading it all into memory; line endings are stripped
     *
     * @return Generator<int, string>
     *
     * @throws FileNotFoundException
     */
    public function lines(): Generator
    {
        $this->assertWithinRoot();

        if (!$this->isFile()) {
            throw new FileNotFoundException(sprintf('%s does not exist.', $this->path));
        }

        $handle = @fopen($this->path, 'r');

        if ($handle === false) {
            throw new RuntimeException(sprintf('Could not read %s.', $this->path));
        }

        return $this->readLines($handle);
    }

    /**
     * Create a directory at the given filepath
     */
    public function makeDirectory(): Utility
    {
        $this->assertWithinRoot();

        if (!$this->filesystem->exists($this->path)) {
            $this->filesystem->mkdir($this->path);
        }

        return new static($this->path, $this->root);
    }

    /**
     * Guess the paths filename
     */
    public function name(bool $withExtension = false): string
    {
        if ($withExtension) {
            return $this->fileInfo()->getFilename();
        }

        if (in_array($this->extension(), ['', '0'], true)) {
            return $this->fileInfo()->getFilename();
        }

        $fileName = $this->fileInfo()->getFilename();
        $extension = $this->extension(true);

        return substr($fileName, 0, strlen($fileName) - strlen($extension));
    }

    /**
     * Get the namespace from the file (if its a PHP file)
     *
     * @throws InvalidFileTypeException
     * @throws FileNotFoundException
     */
    public function namespace(): string
    {
        $this->assertWithinRoot();

        if ($this->exists()) {
            if ($this->fileInfo()->getExtension() === 'php') {
                $code = @file_get_contents($this->path);

                if ($code === false) {
                    throw new FileNotFoundException(sprintf('Could not open %s for reading.', $this->path));
                }

                $namespace = $this->findNamespace($code);

                if (is_null($namespace)) {
                    throw new FileFormatException(sprintf('%s has no namespace', $this->path));
                }

                return $namespace;
            }

            throw new InvalidFileTypeException(sprintf('%s is not a PHP file.', $this->path));
        }

        throw new FileNotFoundException(sprintf('%s does not exist.', $this->path));
    }

    /**
     * Get then path of the file or directory
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Set content to the file
     */
    public function setContent(string $content): Utility
    {
        return $this->putContent($content);
    }

    /**
     * Touch a file or directory, to ensure it exists
     */
    public function touch(): Utility
    {
        $this->assertWithinRoot();

        if (!$this->filesystem->exists($this->path)) {
            // if path has an extension, assume it's a file
            if (!in_array($this->extension(), ['', '0'], true)) {
                $this->touchFile();
            } else {
                return $this->makeDirectory();
            }
        }

        return new static($this->path, $this->root);
    }

    /**
     * Create a directory at the given filepath
     */
    public function touchFile(): Utility
    {
        $this->assertWithinRoot();

        if (!$this->filesystem->exists($this->path)) {
            // ensure the directory exists before touching the file
            $this->filesystem->mkdir(dirname($this->path));
            $this->filesystem->touch($this->path);
        }

        return new static($this->path, $this->root);
    }

    /**
     * Throw if a root is set and the path resolves (via ../ or symlinks) outside it
     */
    protected function assertWithinRoot(): void
    {
        if ($this->root === null) {
            return;
        }

        $root = $this->resolve($this->root);
        $path = $this->resolve($this->path);

        if ($path !== $root && !str_starts_with($path, rtrim($root, '/') . '/')) {
            throw new InvalidArgumentException(sprintf('%s is outside the root %s.', $this->path, $this->root));
        }
    }

    /**
     * Clean up the path for usage
     *
     * @param $path
     */
    protected function cleanPath(string $path): string
    {
        // remove duplicate directory slashes
        $path = preg_replace('#(\/)+#', '$1', $path);

        // change directory slash to platforms seperator
        return (string) preg_replace('#\/#', DIRECTORY_SEPARATOR, (string) $path);
    }

    /**
     * Create a file info instance
     */
    protected function fileInfo(): SplFileInfo
    {
        return new SplFileInfo($this->path);
    }

    /**
     * If the file exists, put content into it
     */
    protected function putContent(string $content, int $flags = 0): Utility
    {
        $this->assertWithinRoot();

        if (!$this->isFile()) {
            throw new FileNotFoundException(sprintf('%s does not exist.', $this->path));
        }

        if ($flags & FILE_APPEND) {
            if (file_put_contents($this->path(), $content, $flags | LOCK_EX) === false) {
                throw new RuntimeException(sprintf('Could not write to %s.', $this->path));
            }

            return new static($this->path, $this->root);
        }

        // write a sibling temp file then rename over the target, so readers never see a partial file
        $target = realpath($this->path) ?: $this->path;
        $temp = tempnam(dirname($target), '.tmp');

        if ($temp === false) {
            throw new RuntimeException(sprintf('Could not write to %s.', $this->path));
        }

        $written = file_put_contents($temp, $content, LOCK_EX);
        $done = $written !== false && chmod($temp, fileperms($target) & 0777) && rename($temp, $target);

        if (!$done) {
            @unlink($temp);

            throw new RuntimeException(sprintf('Could not write to %s.', $this->path));
        }

        return new static($this->path, $this->root);
    }

    /**
     * Find the first namespace declaration in PHP source, ignoring comments, strings and `namespace\foo` operators
     */
    private function findNamespace(string $code): ?string
    {
        $tokens = token_get_all($code);

        foreach ($tokens as $i => $token) {
            if (!is_array($token) || $token[0] !== T_NAMESPACE) {
                continue;
            }

            for ($j = $i + 1; isset($tokens[$j]); $j++) {
                $next = $tokens[$j];

                if (is_array($next) && in_array($next[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
                    continue;
                }

                if (is_array($next) && in_array($next[0], [T_STRING, T_NAME_QUALIFIED], true)) {
                    return $next[1];
                }

                break;
            }
        }

        return null;
    }

    /**
     * @param resource $handle
     *
     * @return Generator<int, string>
     */
    private function readLines($handle): Generator
    {
        try {
            while (($line = fgets($handle)) !== false) {
                yield rtrim($line, "\r\n");
            }
        } finally {
            fclose($handle);
        }
    }

    /**
     * Absolute, normalised path (forward slashes); symlinks resolved for the deepest existing ancestor
     */
    private function resolve(string $path): string
    {
        $path = str_replace('\\', '/', $path);

        if (!preg_match('#^(/|[a-z]:/)#i', $path)) {
            $path = str_replace('\\', '/', (string) getcwd()) . '/' . $path;
        }

        $parts = [];
        foreach (explode('/', $path) as $part) {
            if ($part === '..') {
                array_pop($parts);
            } elseif ($part !== '' && $part !== '.') {
                $parts[] = $part;
            }
        }

        $prefix = str_starts_with($path, '/') ? '/' : '';
        $tail = [];
        while ($parts !== []) {
            $candidate = $prefix . implode('/', $parts);
            $real = realpath($candidate);
            if ($real !== false) {
                return rtrim(str_replace('\\', '/', $real), '/') . ($tail === [] ? '' : '/' . implode('/', $tail));
            }

            array_unshift($tail, array_pop($parts));
        }

        return $prefix . implode('/', $tail);
    }
}
