<?php

namespace Tests;

class TouchFileTest extends BaseTestSuite
{
    public function testUtilityCanCreateFileIfItDoesNotExist(): void
    {
        $tempFile = $this->tempFileName();

        $helper = $this->utility($tempFile);

        $this->assertFalse($helper->exists());

        $helper->touchFile();

        $this->assertTrue($helper->exists());

        $this->assertTrue(is_file($tempFile));

        $helper->delete();

        $this->assertFalse($helper->exists());
    }
}
