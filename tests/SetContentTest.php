<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;

final class SetContentTest extends BaseTestSuite
{
    public function testThrowsErrorIfFileDoesNotExistWhenAppending(): void
    {
        $tempFile = $this->tempFileName();

        $utility = $this->utility($tempFile);
        $this->expectException(FileNotFoundException::class);
        $utility->setContent('hello');
    }

    public function testUtilitySetsContentOfFile(): void
    {
        $tempFile = $this->makeTempFile();

        $utility = $this->utility($tempFile);
        $this->assertSame('hello', $utility->setContent('hello')->content());
        $this->assertSame('world', $utility->setContent('world')->content());
    }
}
