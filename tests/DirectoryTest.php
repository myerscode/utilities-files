<?php

namespace Tests;

class DirectoryTest extends BaseTestSuite
{
    public function testUtilityReturnsThePathsDirectoryIfItIsAFile(): void
    {
        $helper = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));

        $this->assertEquals(rtrim($this->resourceFilePath(''), DIRECTORY_SEPARATOR), $helper->directory());
    }

    public function testUtilityReturnsThePathsFullPathIfPathIsADirectory(): void
    {
        $helper = $this->utility($this->resourceFilePath(''));

        $this->assertEquals(rtrim($this->resourceFilePath(''), DIRECTORY_SEPARATOR), $helper->directory());
    }
}
