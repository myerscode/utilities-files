<?php

namespace Tests;

class DeleteTest extends BaseTestSuite
{
    public function testUtilityCanDeleteADirectory(): void
    {
        $tempDirectory = $this->tempDirectoryName();

        $helper = $this->utility($tempDirectory);

        $helper->makeDirectory();

        $this->assertTrue($helper->exists());

        $helper->delete();

        $this->assertFalse($helper->exists());
    }

    public function testUtilityCanDeleteAFile(): void
    {
        $tempDirectory = $this->tempFileName();

        $helper = $this->utility($tempDirectory);

        $helper->touchFile();

        $this->assertTrue($helper->exists());

        $helper->delete();

        $this->assertFalse($helper->exists());
    }
}
