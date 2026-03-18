<?php

declare(strict_types=1);

namespace Tests;

final class IsDirectoryTest extends BaseTestSuite
{
    public function testReturnsFalseIfDirectoryNotFound(): void
    {
        $tempFile = $this->makeTempDirectory();

        $utility = $this->utility($tempFile);

        $utility->delete();

        $this->assertFalse($utility->isDirectory());
    }

    public function testReturnsTrueIfPathIsADirectory(): void
    {
        $tempFile = $this->makeTempDirectory();

        $utility = $this->utility($tempFile);

        $this->assertTrue($utility->isDirectory());

        $utility->delete();
    }
}
