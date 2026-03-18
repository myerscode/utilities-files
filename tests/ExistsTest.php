<?php

declare(strict_types=1);

namespace Tests;

final class ExistsTest extends BaseTestSuite
{
    public function testUtilityCanCheckForADirectoriesExistence(): void
    {
        $tempDirectory = $this->tempDirectoryName();

        $utility = $this->utility($tempDirectory);

        $this->assertFalse($utility->exists());

        $this->makeTempDirectory($tempDirectory);

        $this->assertTrue($utility->exists());

        $utility->delete();

        $this->assertFalse($utility->exists());
    }

    public function testUtilityCanCheckForAFilesExistence(): void
    {
        $tempFile = $this->tempFileName();

        $utility = $this->utility($tempFile);

        $this->assertFalse($utility->exists());

        $this->makeTempFile($tempFile);

        $this->assertTrue($utility->exists());

        $utility->delete();

        $this->assertFalse($utility->exists());
    }
}
