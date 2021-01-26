<?php

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;
use Myerscode\Utilities\Files\Exceptions\InvalidFileTypeException;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class FullyQualifiedClassnameTest extends BaseTestSuite
{

    /**
     * @covers ::fullyQualifiedClassname
     */
    public function testUtilityCanFindPHPClassName()
    {
        $helper = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertEquals('Tests\Resources\RandomClassForTestingWith', $helper->fullyQualifiedClassname());
    }

    /**
     * @covers ::fullyQualifiedClassname
     */
    public function testUtilityThrowsErrorIfFileIsNotPHP()
    {

        $helper = $this->utility($this->resourceFilePath('RandomFileForTestingWith.js'));
        $this->expectException(InvalidFileTypeException::class);
        $helper->fullyQualifiedClassname();
    }

    /**
     * @covers ::fullyQualifiedClassname
     */
    public function testUtilityThrowsErrorIfFileIsNotFound()
    {

        $helper = $this->utility($this->resourceFilePath('RandomFileForTestingWith.whoops'));
        $this->expectException(FileNotFoundException::class);
        $helper->fullyQualifiedClassname();
    }
}
