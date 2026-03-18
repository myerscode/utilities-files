<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileFormatExpection;
use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;
use Myerscode\Utilities\Files\Exceptions\InvalidFileTypeException;

final class NamespaceTest extends BaseTestSuite
{
    public function testUtilityCanFindPHPNamespace(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertSame('Tests\Resources', $utility->namespace());
    }

    public function testUtilityThrowsErrorIfFileDoesNotHaveANamespace(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomPHPFileForTestingWithoutNamespace.php'));
        $this->expectException(FileFormatExpection::class);
        $utility->namespace();
    }

    public function testUtilityThrowsErrorIfFileIsNotFound(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomFileForTestingWith.whoops'));
        $this->expectException(FileNotFoundException::class);
        $utility->namespace();
    }

    public function testUtilityThrowsErrorIfFileIsNotPHP(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomFileForTestingWith.js'));
        $this->expectException(InvalidFileTypeException::class);
        $utility->namespace();
    }
}
