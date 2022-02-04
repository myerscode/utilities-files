<?php

namespace Tests;

use Myerscode\Utilities\Files\Utility;
use PHPUnit\Framework\TestCase;

abstract class BaseTestSuite extends TestCase
{
    /**
     * Utility class name
     *
     * @var string $utility
     */
    public $utility = Utility::class;

    public function makeTempDirectory(string $directory = null): string
    {
        if (is_null($directory)) {
            $directory = $this->tempDirectoryName();
        }

        mkdir($directory);

        return $directory;
    }

    public function makeTempFile(string $file = null): string
    {
        if (is_null($file)) {
            $file = $this->tempFileName();
        }

        touch($file);

        return $file;
    }

    public function resourceFilePath(string $file): string
    {
        $dir = __DIR__ . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR;

        return $dir . $file;
    }

    public function tempDirectoryName(): string
    {
        return sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('myerscode');
    }

    public function tempFileName($extension = '.tmp'): string
    {
        return sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('myerscode') . $extension;
    }

    /**
     * @param $config
     */
    public function utility($config): Utility
    {
        return new Utility($config);
    }
}
