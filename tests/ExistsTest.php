<?php

namespace Tests;

class ExistsTest extends BaseTestSuite
{
    public function testUtilityCanCheckForADirectoriesExistence(): void
    {
        $tempDirectory = $this->tempDirectoryName();

        $helper = $this->utility($tempDirectory);

        $this->assertFalse($helper->exists());

        $this->makeTempDirectory($tempDirectory);

        $this->assertTrue($helper->exists());

        $helper->delete();

        $this->assertFalse($helper->exists());
    }

    public function testUtilityCanCheckForAFilesExistence(): void
    {
        $tempFile = $this->tempFileName();

        $helper = $this->utility($tempFile);

        $this->assertFalse($helper->exists());

        $this->makeTempFile($tempFile);

        $this->assertTrue($helper->exists());

        $helper->delete();

        $this->assertFalse($helper->exists());
    }
}
