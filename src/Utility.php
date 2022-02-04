<?php

namespace Myerscode\Utilities\Files;

use Myerscode\Utilities\Files\Exceptions\FileFormatExpection;
use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;
use Myerscode\Utilities\Files\Exceptions\InvalidFileTypeException;
use Myerscode\Utilities\Files\Exceptions\NotADirectoryException;
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
    /**
     * @var Filesystem
     */
    private readonly Filesystem $filesystem;

    /**
     * @var Finder
     */
    private readonly Finder $finder;

    private readonly string $path;


    public function __construct(string $path)
    {
        $this->path = $this->cleanPath($path);
        $this->filesystem = new Filesystem();
        $this->finder = new Finder();
    }

    /**
     * Create a new instance of the files utility
     *
     * @param $path
     *
     * @return Utility
     */
    public static function make($path): static
    {
        return new static($path);
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
        if ($this->filesystem->exists($this->path)) {
            $this->filesystem->remove($this->path);
        }

        return new self($this->path);
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

            usort($fileList, fn($a, $b): int => strcmp($a->getRelativePathname(), $b->getRelativePathname()));

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
     * Guess the paths filename
     */
    public function name(bool $withExtension = false): string
    {
        if ($withExtension) {
            return $this->fileInfo()->getFilename();
        }
        if (empty($this->extension())) {
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
                $handle = fopen(addslashes($this->path), "r");
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
        if (!$this->filesystem->exists($this->path)) {
            // if has has a extension, assume its a file
            if (!empty($this->extension())) {
                // ensure the directory exists before touching the file
                $this->filesystem->mkdir(dirname($this->path));
                $this->filesystem->touch($this->path);
            } else {
                $this->filesystem->mkdir($this->path);
            }
        }

        return new self($this->path);
    }

    /**
     * Clean up the path for usage
     *
     * @param $path
     *
     * @return string
     */
    protected function cleanPath($path): string
    {
        // remove duplicate directory slashes
        $path = preg_replace('#(\/)+#', '$1', $path);

        // change directory slash to platforms seperator
        return preg_replace('#\/#', DIRECTORY_SEPARATOR, $path);
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
        if ($this->isFile()) {
            file_put_contents($this->path(), $content, $flags);
        } else {
            throw new FileNotFoundException(sprintf('%s does not exist.', $this->path));
        }

        return new self($this->path);
    }
}
