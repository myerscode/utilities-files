<?php

namespace Tests;

class IsFileTest extends BaseTestSuite
{
    public function testReturnsFalseIfFileNotFound(): void
    {
        $tempFile = $this->makeTempFile();

        $helper = $this->utility($tempFile);

        $helper->delete();

        $this->assertFalse($helper->isFile());
    }

    public function testReturnsTrueIfPathIsAFile(): void
    {
        $tempFile = $this->makeTempFile();

        $helper = $this->utility($tempFile);

        $this->assertTrue($helper->isFile());

        $helper->delete();
    }
}
