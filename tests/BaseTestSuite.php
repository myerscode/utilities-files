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


    /**
     * @param $config
     *
     * @return Utility
     */
    public function utility($config)
    {
        return new Utility($config);
    }

    public function tempDirectoryName(): string
    {
        return sys_get_temp_dir().'/'.uniqid('myerscode');
    }

    public function tempFileName($extension = '.tmp'): string
    {
        return sys_get_temp_dir().'/'.uniqid('myerscode').$extension;
    }

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

}
