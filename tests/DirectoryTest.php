<?php

namespace Tests;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class DirectoryTest extends BaseTestSuite
{

    public function testUtilityReturnsThePathsDirectoryIfItIsAFile()
    {
        $helper = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));

        $this->assertEquals(rtrim($this->resourceFilePath(''), DIRECTORY_SEPARATOR), $helper->directory());
    }

    public function testUtilityReturnsThePathsFullPathIfPathIsADirectory()
    {
        $helper = $this->utility($this->resourceFilePath(''));

        $this->assertEquals(rtrim($this->resourceFilePath(''), DIRECTORY_SEPARATOR), $helper->directory());
    }
}
