<?php

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileFormatExpection;
use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;
use Myerscode\Utilities\Files\Exceptions\InvalidFileTypeException;

class NamespaceTest extends BaseTestSuite
{
    public function testUtilityCanFindPHPNamespace(): void
    {
        $helper = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertEquals('Tests\Resources', $helper->namespace());
    }

    public function testUtilityThrowsErrorIfFileDoesNotHaveANamespace(): void
    {
        $helper = $this->utility($this->resourceFilePath('RandomPHPFileForTestingWithoutNamespace.php'));
        $this->expectException(FileFormatExpection::class);
        $helper->namespace();
    }

    public function testUtilityThrowsErrorIfFileIsNotFound(): void
    {
        $helper = $this->utility($this->resourceFilePath('RandomFileForTestingWith.whoops'));
        $this->expectException(FileNotFoundException::class);
        $helper->namespace();
    }

    public function testUtilityThrowsErrorIfFileIsNotPHP(): void
    {
        $helper = $this->utility($this->resourceFilePath('RandomFileForTestingWith.js'));
        $this->expectException(InvalidFileTypeException::class);
        $helper->namespace();
    }
}
