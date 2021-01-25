<?php

namespace Tests;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class IsFileTest extends BaseTestSuite
{

    /**
     * @covers ::isFile
     */
    public function testReturnsTrueIfPathIsAFile()
    {
        $tempFile = $this->makeTempFile();

        $helper = $this->utility($tempFile);

        $this->assertTrue($helper->isFile());

        $helper->delete();
    }

    /**
     * @covers ::isFile
     */
    public function testReturnsFalseIfFileNotFound()
    {
        $tempFile = $this->makeTempFile();

        $helper = $this->utility($tempFile);

        $helper->delete();

        $this->assertFalse($helper->isFile());
    }
}
