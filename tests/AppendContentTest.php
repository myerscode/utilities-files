<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;

final class AppendContentTest extends BaseTestSuite
{
    public function testThrowsErrorIfFileDoesNotExistWhenAppending(): void
    {
        $tempFile = $this->tempFileName();

        $utility = $this->utility($tempFile);
        $this->expectException(FileNotFoundException::class);
        $utility->appendContent('hello');
    }

    public function testUtilityAppendsContentToFile(): void
    {
        $tempFile = $this->makeTempFile();

        $utility = $this->utility($tempFile);
        $this->assertSame('hello', $utility->appendContent('hello')->content());
        $this->assertSame('hello world', $utility->appendContent(' world')->content());
    }
}
