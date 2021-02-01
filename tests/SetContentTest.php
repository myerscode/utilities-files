<?php

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class SetContentTest extends BaseTestSuite
{

    public function testUtilitySetsContentOfFile()
    {
        $tempFile = $this->makeTempFile();

        $helper = $this->utility($tempFile);
        $this->assertEquals('hello', $helper->setContent('hello')->content());
        $this->assertEquals('world', $helper->setContent('world')->content());
    }

    public function testThrowsErrorIfFileDoesNotExistWhenAppending()
    {
        $tempFile = $this->tempFileName();

        $helper = $this->utility($tempFile);
        $this->expectException(FileNotFoundException::class);
        $helper->setContent('hello');
    }

}
