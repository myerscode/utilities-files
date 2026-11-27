<?php

declare(strict_types=1);

namespace Tests;

final class DirectoryTest extends BaseTestSuite
{
    public function testExistingDirectoryWithDotInNameIsTreatedAsADirectory(): void
    {
        $parent = $this->makeTempDirectory();
        $directory = $parent . DIRECTORY_SEPARATOR . 'Rupert.v1.2';
        mkdir($directory);

        $this->assertSame($directory, $this->utility($directory)->directory());

        rmdir($directory);
        rmdir($parent);
    }

    public function testNonExistentPathWithExtensionIsStillAssumedToBeAFile(): void
    {
        $this->assertSame('Gerald', $this->utility('Gerald/Arthur.txt')->directory());
    }
    public function testUtilityReturnsThePathsDirectoryIfItIsAFile(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));

        $this->assertSame(rtrim($this->resourceFilePath(''), DIRECTORY_SEPARATOR), $utility->directory());
    }

    public function testUtilityReturnsThePathsFullPathIfPathIsADirectory(): void
    {
        $utility = $this->utility($this->resourceFilePath(''));

        $this->assertSame(rtrim($this->resourceFilePath(''), DIRECTORY_SEPARATOR), $utility->directory());
    }
}
