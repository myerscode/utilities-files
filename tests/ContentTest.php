<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;

final class ContentTest extends BaseTestSuite
{
    public function testThrowsErrorIfFileDoesNotExistWheGettingContent(): void
    {
        $tempFile = $this->tempFileName();

        $utility = $this->utility($tempFile);
        $this->expectException(FileNotFoundException::class);
        $utility->content();
    }

    public function testUtilityGetContent(): void
    {
        $tempFile = $this->makeTempFile();
        file_put_contents($tempFile, 'hello world');
        $utility = $this->utility($tempFile);
        $this->assertSame('hello world', $utility->content());
    }
}
