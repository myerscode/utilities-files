<?php

namespace Tests;

class TouchTest extends BaseTestSuite
{
    public function testUtilityCanCreateDirectoryIfItDoesNotExist(): void
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

    public function testUtilityCanCreateFileIfItDoesNotExist(): void
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
