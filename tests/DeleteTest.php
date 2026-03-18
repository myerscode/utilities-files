<?php

declare(strict_types=1);

namespace Tests;

final class DeleteTest extends BaseTestSuite
{
    public function testUtilityCanDeleteADirectory(): void
    {
        $tempDirectory = $this->tempDirectoryName();

        $utility = $this->utility($tempDirectory);

        $utility->makeDirectory();

        $this->assertTrue($utility->exists());

        $utility->delete();

        $this->assertFalse($utility->exists());
    }

    public function testUtilityCanDeleteAFile(): void
    {
        $tempDirectory = $this->tempFileName();

        $utility = $this->utility($tempDirectory);

        $utility->touchFile();

        $this->assertTrue($utility->exists());

        $utility->delete();

        $this->assertFalse($utility->exists());
    }
}
