<?php

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class ContentTest extends BaseTestSuite
{

    public function testUtilityGetContent()
    {
        $tempFile = $this->makeTempFile();
        file_put_contents($tempFile, 'hello world');
        $helper = $this->utility($tempFile);
        $this->assertEquals('hello world', $helper->content());
    }

    public function testThrowsErrorIfFileDoesNotExistWheGettingContent()
    {
        $tempFile = $this->tempFileName();

        $helper = $this->utility($tempFile);
        $this->expectException(FileNotFoundException::class);
        $helper->content();
    }

}
