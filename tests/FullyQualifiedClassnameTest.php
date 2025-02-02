<?php

namespace Tests;

use Tests\Resources\RandomClassForTestingWith;
use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;
use Myerscode\Utilities\Files\Exceptions\InvalidFileTypeException;

class FullyQualifiedClassnameTest extends BaseTestSuite
{
    public function testUtilityCanFindPHPClassName(): void
    {
        $helper = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertEquals(RandomClassForTestingWith::class, $helper->fullyQualifiedClassname());
    }

    public function testUtilityThrowsErrorIfFileIsNotFound(): void
    {
        $helper = $this->utility($this->resourceFilePath('RandomFileForTestingWith.whoops'));
        $this->expectException(FileNotFoundException::class);
        $helper->fullyQualifiedClassname();
    }

    public function testUtilityThrowsErrorIfFileIsNotPHP(): void
    {
        $helper = $this->utility($this->resourceFilePath('RandomFileForTestingWith.js'));
        $this->expectException(InvalidFileTypeException::class);
        $helper->fullyQualifiedClassname();
    }
}
