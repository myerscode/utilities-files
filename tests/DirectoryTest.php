<?php

declare(strict_types=1);

namespace Tests;

final class DirectoryTest extends BaseTestSuite
{
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
