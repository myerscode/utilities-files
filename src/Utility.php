<?php

namespace Myerscode\Utilities\Files;

use InvalidArgumentException;
use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;
use Myerscode\Utilities\Files\Exceptions\InvalidFileTypeException;
use Myerscode\Utilities\Files\Exceptions\NotADirectoryException;
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
    private $filesystem;

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var string
     */
    private $path;


    public function __construct(string $path)
    {
        $this->path = $path;
        $this->filesystem = new Filesystem();
        $this->finder = new Finder();
    }

    /**
     * Get the assumed PRS4 class name for the file
     *
     * @return string
     */
    public function className(): string
    {
        $directoriesAndFilename = explode('/', $this->path);
        $filename = array_pop($directoriesAndFilename);
        $nameAndExtension = explode('.', $filename);

        return array_shift($nameAndExtension);
    }

    /**
     * Delete the file or directory with the given path
     *
     * @return Utility
     */
    public function delete(): Utility
    {
        if ($this->filesystem->exists($this->path)) {
            $this->filesystem->remove($this->path);
        }

        return new self($this->path);
    }

    /**
     * Does the file or directory actually exists
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->filesystem->exists($this->path);
    }

    /**
     * Get a collection of spl file objects, from within the path if it's a directory
     *
     * @return array
     */
    public function files(): array
    {
        if ($this->isDirectory()) {
            return iterator_to_array($this->finder->files()->in($this->path)->getIterator(), false);
        }

        throw new NotADirectoryException();
    }

    /**
     * Get the fully qualified class name from the file (if it's a PHP file)
     *
     * @return string
     *
     * @throws InvalidFileTypeException
     * @throws FileNotFoundException
     */
    public function fullyQualifiedClassname(): string
    {
        if ($this->exists()) {
            if (pathinfo($this->path, PATHINFO_EXTENSION) === 'php') {
                return $this->namespace().'\\'.$this->className();
            }
            throw new InvalidFileTypeException("$this->path is not a PHP file.");
        }
        throw new FileNotFoundException("$this->path does not exist.");
    }

    /**
     * Is the path a directory
     *
     * @return bool
     */
    public function isDirectory(): bool
    {
        return is_dir($this->path);
    }

    /**
     * Is the path a file
     *
     * @return bool
     */
    public function isFile(): bool
    {
        return is_file($this->path);
    }

    /**
     * Get the namespace from the file (if its a PHP file)
     *
     * @return string
     *
     * @throws InvalidFileTypeException
     * @throws FileNotFoundException
     */
    public function namespace(): string
    {
        if ($this->exists()) {
            if (pathinfo($this->path, PATHINFO_EXTENSION) === 'php') {
                $lines = file($this->path);
                $array = preg_grep('/^namespace /', $lines);
                $namespaceLine = array_shift($array);
                $match = [];
                preg_match('/^namespace (.*);$/', $namespaceLine, $match);

                return array_pop($match);
            }
            throw new InvalidFileTypeException("$this->path is not a PHP file.");
        }

        throw new FileNotFoundException("$this->path does not exist.");
    }

    /**
     * Get then path of the file or directory
     *
     * @return string
     */
    public function path() : string
    {
        return $this->path;
    }

    /**
     * Touch a file or directory, to ensure it exists
     */
    public function touch(): Utility
    {
        if (!$this->filesystem->exists($this->path)) {
            // if has has a extension, assume its a file
            if ('' !== pathinfo($this->path, PATHINFO_EXTENSION)) {
                $this->filesystem->touch($this->path);
            } else {
                $this->filesystem->mkdir($this->path);
            }
        }

        return new self($this->path);
    }

}
