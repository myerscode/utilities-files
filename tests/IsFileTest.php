<?php

declare(strict_types=1);

namespace Tests;

final class IsFileTest extends BaseTestSuite
{
    public function testReturnsFalseIfFileNotFound(): void
    {
        $tempFile = $this->makeTempFile();

        $utility = $this->utility($tempFile);

        $utility->delete();

        $this->assertFalse($utility->isFile());
    }

    public function testReturnsTrueIfPathIsAFile(): void
    {
        $tempFile = $this->makeTempFile();

        $utility = $this->utility($tempFile);

        $this->assertTrue($utility->isFile());

        $utility->delete();
    }
}
