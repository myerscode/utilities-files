<?php

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;

class SetContentTest extends BaseTestSuite
{
    public function testThrowsErrorIfFileDoesNotExistWhenAppending(): void
    {
        $tempFile = $this->tempFileName();

        $helper = $this->utility($tempFile);
        $this->expectException(FileNotFoundException::class);
        $helper->setContent('hello');
    }

    public function testUtilitySetsContentOfFile(): void
    {
        $tempFile = $this->makeTempFile();

        $helper = $this->utility($tempFile);
        $this->assertEquals('hello', $helper->setContent('hello')->content());
        $this->assertEquals('world', $helper->setContent('world')->content());
    }
}
