<?php

namespace Tests;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class TouchTest extends BaseTestSuite
{

    /**
     * @covers ::touch
     */
    public function testUtilityCanCreateDirectoryIfItDoesNotExist()
    {
        $tempDirectory = $this->tempDirectoryName();

        $helper = $this->utility($tempDirectory);

        $this->assertFalse($helper->exists());

        $helper->touch();

        $this->assertTrue($helper->exists());

        $this->assertTrue(is_dir($tempDirectory));

        $helper->delete();

        $this->assertFalse($helper->exists());
    }

    /**
     * @covers ::touch
     */
    public function testUtilityCanCreateFileIfItDoesNotExist()
    {
        $tempFile = $this->tempFileName();

        $helper = $this->utility($tempFile);

        $this->assertFalse($helper->exists());

        $helper->touch();

        $this->assertTrue($helper->exists());

        $this->assertTrue(is_file($tempFile));

        $helper->delete();

        $this->assertFalse($helper->exists());
    }
}
