<?php

namespace Tests;

class MakeDirectoryTest extends BaseTestSuite
{
    public function testUtilityCanCreateDirectoryIfItDoesNotExist(): void
    {
        $tempDirectory = $this->tempDirectoryName();

        $helper = $this->utility($tempDirectory);

        $this->assertFalse($helper->exists());

        $helper->makeDirectory();

        $this->assertTrue($helper->exists());

        $this->assertTrue(is_dir($tempDirectory));

        $helper->delete();

        $this->assertFalse($helper->exists());
    }
}
