<?php

namespace Myerscode\Utilities\Files;

use Myerscode\Utilities\Files\Exceptions\FileFormatExpection;
use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;
use Myerscode\Utilities\Files\Exceptions\InvalidFileTypeException;
use Myerscode\Utilities\Files\Exceptions\NotADirectoryException;
use InvalidArgumentException;
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

    private readonly Finder $finder;

    private readonly string $path;

    private readonly ?string $root;

    /**
     * @param string|null $root Optional base directory; mutating operations (delete, write, append, touch,
     *                          makeDirectory) are refused for paths that resolve outside it.
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
        $this->finder = new Finder();
    }

    /**
     * Create a new instance of the files utility
     *
     * @param $path
     * @param string|null $root Optional base directory to confine mutating operations to
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
        $directoriesAndFilename = explode(DIRECTORY_SEPARATOR, $this->path);
        $filename = array_pop($directoriesAndFilename);
        $nameAndExtension = explode('.', $filename);

        return array_shift($nameAndExtension);
    }

    /**
     * If the file exists get its content
     */
    public function content(): bool|string
    {
        if ($this->isFile()) {
            return file_get_contents($this->path());
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

        return new self($this->path, $this->root);
    }

    /**
     * If the path is assumed to be a file, return its directory path
     * If the path is assumed to be a directory return its path
     */
    public function directory(): string
    {
        // if has has a extension, assume its a file
        //!empty($this->extension())
        if ($this->extension() !== '' && $this->extension() !== '0') {
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
        if ($this->isDirectory()) {
            $fileList = iterator_to_array($this->finder->files()->in($this->path)->getIterator(), false);

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
     * Create a directory at the given filepath
     */
    public function makeDirectory(): Utility
    {
        $this->assertWithinRoot();

        if (!$this->filesystem->exists($this->path)) {
            $this->filesystem->mkdir($this->path);
        }

        return new self($this->path, $this->root);
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
        if ($this->exists()) {
            if ($this->fileInfo()->getExtension() === 'php') {
                $handle = fopen($this->path, 'r');

                if ($handle === false) {
                    throw new FileNotFoundException(sprintf('Could not open %s for reading.', $this->path));
                }

                $namespace = null;

                while (($line = fgets($handle)) !== false) {
                    if (str_starts_with($line, 'namespace')) {
                        $parts = explode(' ', $line);
                        $namespace = rtrim(trim($parts[1]), ';');
                        break;
                    }
                }

                fclose($handle);

                if (is_null($namespace)) {
                    throw new FileFormatExpection(sprintf('%s has no namespace', $this->path));
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

        return new self($this->path, $this->root);
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

        return new self($this->path, $this->root);
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

        if ($this->isFile()) {
            file_put_contents($this->path(), $content, $flags);
        } else {
            throw new FileNotFoundException(sprintf('%s does not exist.', $this->path));
        }

        return new self($this->path, $this->root);
    }
}
