<?php

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileFormatExpection;
use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;
use Myerscode\Utilities\Files\Exceptions\InvalidFileTypeException;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class NamespaceTest extends BaseTestSuite
{

    /**
     * @covers ::namespace
     */
    public function testUtilityCanFindPHPNamespace()
    {

        $helper = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertEquals('Tests\Resources', $helper->namespace());
    }

    /**
     * @covers ::namespace
     */
    public function testUtilityThrowsErrorIfFileIsNotPHP()
    {

        $helper = $this->utility($this->resourceFilePath('RandomFileForTestingWith.js'));
        $this->expectException(InvalidFileTypeException::class);
        $helper->namespace();
    }

    /**
     * @covers ::namespace
     */
    public function testUtilityThrowsErrorIfFileIsNotFound()
    {

        $helper = $this->utility($this->resourceFilePath('RandomFileForTestingWith.whoops'));
        $this->expectException(FileNotFoundException::class);
        $helper->namespace();
    }

    /**
     * @covers ::namespace
     */
    public function testUtilityThrowsErrorIfFileDoesNotHaveANamespace()
    {

        $helper = $this->utility($this->resourceFilePath('RandomPHPFileForTestingWithoutNamespace.php'));
        $this->expectException(FileFormatExpection::class);
        $helper->namespace();
    }
}
