<?php

namespace Tests;

class IsDirectoryTest extends BaseTestSuite
{
    public function testReturnsFalseIfDirectoryNotFound(): void
    {
        $tempFile = $this->makeTempDirectory();

        $helper = $this->utility($tempFile);

        $helper->delete();

        $this->assertFalse($helper->isDirectory());
    }

    public function testReturnsTrueIfPathIsADirectory(): void
    {
        $tempFile = $this->makeTempDirectory();

        $helper = $this->utility($tempFile);

        $this->assertTrue($helper->isDirectory());

        $helper->delete();
    }
}
