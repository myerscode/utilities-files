<?php

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class AppendContentTest extends BaseTestSuite
{

    public function testUtilityAppendsContentToFile()
    {
        $tempFile = $this->makeTempFile();

        $helper = $this->utility($tempFile);
        $this->assertEquals('hello', $helper->appendContent('hello')->content());
        $this->assertEquals('hello world', $helper->appendContent(' world')->content());
    }

    public function testThrowsErrorIfFileDoesNotExistWhenAppending()
    {
        $tempFile = $this->tempFileName();

        $helper = $this->utility($tempFile);
        $this->expectException(FileNotFoundException::class);
        $helper->appendContent('hello');
    }

}
