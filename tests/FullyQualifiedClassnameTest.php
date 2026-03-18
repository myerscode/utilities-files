<?php

declare(strict_types=1);

namespace Tests;

use Tests\Resources\RandomClassForTestingWith;
use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;
use Myerscode\Utilities\Files\Exceptions\InvalidFileTypeException;

final class FullyQualifiedClassnameTest extends BaseTestSuite
{
    public function testUtilityCanFindPHPClassName(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertSame(RandomClassForTestingWith::class, $utility->fullyQualifiedClassname());
    }

    public function testUtilityThrowsErrorIfFileIsNotFound(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomFileForTestingWith.whoops'));
        $this->expectException(FileNotFoundException::class);
        $utility->fullyQualifiedClassname();
    }

    public function testUtilityThrowsErrorIfFileIsNotPHP(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomFileForTestingWith.js'));
        $this->expectException(InvalidFileTypeException::class);
        $utility->fullyQualifiedClassname();
    }
}
