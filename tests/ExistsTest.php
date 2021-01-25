<?php

namespace Tests;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class ExistsTest extends BaseTestSuite
{

    /**
     * @covers ::exists
     */
    public function testUtilityCanCheckForAFilesExistence()
    {
        $tempFile = $this->tempFileName();

        $helper = $this->utility($tempFile);

        $this->assertFalse($helper->exists());

        $this->makeTempFile($tempFile);

        $this->assertTrue($helper->exists());

        $helper->delete();

        $this->assertFalse($helper->exists());
    }

    /**
     * @covers ::exists
     */
    public function testUtilityCanCheckForADirectoriesExistence()
    {
        $tempDirectory = $this->tempDirectoryName();

        $helper = $this->utility($tempDirectory);

        $this->assertFalse($helper->exists());

        $this->makeTempDirectory($tempDirectory);

        $this->assertTrue($helper->exists());

        $helper->delete();

        $this->assertFalse($helper->exists());
    }
}
