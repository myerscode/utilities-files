<?php

namespace Myerscode\Utilities\Files;

use Myerscode\Utilities\Files\Exceptions\FileFormatExpection;
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
        $this->path = $this->cleanPath($path);
        $this->filesystem = new Filesystem();
        $this->finder = new Finder();
    }

    /**
     * Append content to the file
     *
     * @param  string  $content
     *
     * @return Utility
     */
    public function appendContent(string $content): Utility
    {
        return $this->putContent($content, FILE_APPEND);
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
        $path = preg_replace('/(\/)+/', '$1', $path);

        // change directory slash to platforms seperator
        $path = preg_replace('/\//', DIRECTORY_SEPARATOR, $path);

        return $path;
    }

    /**
     * If the file exists get its content
     *
     * @return string
     */
    public function content(): string
    {
        if ($this->isFile()) {
            return file_get_contents($this->path());
        } else {
            throw new FileNotFoundException("$this->path does not exist.");
        }
    }

    /**
     * Get the assumed PRS4 class name for the file
     *
     * @return string
     */
    public function className(): string
    {
        $directoriesAndFilename = explode(DIRECTORY_SEPARATOR, $this->path);
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
            $fileList = iterator_to_array($this->finder->files()->in($this->path)->getIterator(), false);

            usort($fileList, function ($a, $b) {
                return strcmp($a->getRelativePathname(), $b->getRelativePathname());
            });

            return $fileList;
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
                return $this->namespace() . '\\' . $this->className();
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
     * Create a new instance of the files utility
     *
     * @param $path
     *
     * @return Utility
     */
    public static function make($path): Utility
    {
        return new static($path);
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
                $handle = fopen(addslashes($this->path), "r");
                $namespace = null;

                while (($line = fgets($handle)) !== false) {
                    if (strpos($line, 'namespace') === 0) {
                        $parts = explode(' ', $line);
                        $namespace = rtrim(trim($parts[1]), ';');
                        break;
                    }
                }
                fclose($handle);

                if (is_null($namespace)) {
                    throw new FileFormatExpection("$this->path has no namespace");
                }

                return $namespace;
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
    public function path(): string
    {
        return $this->path;
    }

    /**
     * If the file exists, put content into it
     *
     * @param  string  $content
     * @param  int  $flags
     *
     * @return Utility
     */
    protected function putContent(string $content, int $flags = 0): Utility
    {
        if ($this->isFile()) {
            file_put_contents($this->path(), $content, $flags);
        } else {
            throw new FileNotFoundException("$this->path does not exist.");
        }

        return new self($this->path);
    }

    /**
     * Set content to the file
     *
     * @param  string  $content
     *
     * @return Utility
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
            if ('' !== pathinfo($this->path, PATHINFO_EXTENSION)) {
                $this->filesystem->touch($this->path);
            } else {
                $this->filesystem->mkdir($this->path);
            }
        }

        return new self($this->path);
    }
}
