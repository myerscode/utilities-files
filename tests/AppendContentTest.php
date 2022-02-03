<?php

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;

class AppendContentTest extends BaseTestSuite
{
    public function testThrowsErrorIfFileDoesNotExistWhenAppending(): void
    {
        $tempFile = $this->tempFileName();

        $helper = $this->utility($tempFile);
        $this->expectException(FileNotFoundException::class);
        $helper->appendContent('hello');
    }

    public function testUtilityAppendsContentToFile(): void
    {
        $tempFile = $this->makeTempFile();

        $helper = $this->utility($tempFile);
        $this->assertEquals('hello', $helper->appendContent('hello')->content());
        $this->assertEquals('hello world', $helper->appendContent(' world')->content());
    }
}
