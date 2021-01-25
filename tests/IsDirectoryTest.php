<?php

namespace Tests;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class IsDirectoryTest extends BaseTestSuite
{

    /**
     * @covers ::isDirectory
     */
    public function testReturnsTrueIfPathIsADirectory()
    {
        $tempFile = $this->makeTempDirectory();

        $helper = $this->utility($tempFile);

        $this->assertTrue($helper->isDirectory());

        $helper->delete();
    }

    /**
     * @covers ::isDirectory
     */
    public function testReturnsFalseIfDirectoryNotFound()
    {
        $tempFile = $this->makeTempDirectory();

        $helper = $this->utility($tempFile);

        $helper->delete();

        $this->assertFalse($helper->isDirectory());
    }
}
