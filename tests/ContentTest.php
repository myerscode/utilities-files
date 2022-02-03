<?php

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;

class ContentTest extends BaseTestSuite
{
    public function testThrowsErrorIfFileDoesNotExistWheGettingContent(): void
    {
        $tempFile = $this->tempFileName();

        $helper = $this->utility($tempFile);
        $this->expectException(FileNotFoundException::class);
        $helper->content();
    }

    public function testUtilityGetContent(): void
    {
        $tempFile = $this->makeTempFile();
        file_put_contents($tempFile, 'hello world');
        $helper = $this->utility($tempFile);
        $this->assertEquals('hello world', $helper->content());
    }
}
