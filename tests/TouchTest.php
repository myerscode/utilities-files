<?php

declare(strict_types=1);

namespace Tests;

final class TouchTest extends BaseTestSuite
{
    public function testUtilityCanCreateDirectoryIfItDoesNotExist(): void
    {
        $tempDirectory = $this->tempDirectoryName();

        $utility = $this->utility($tempDirectory);

        $this->assertFalse($utility->exists());

        $utility->touch();

        $this->assertTrue($utility->exists());

        $this->assertDirectoryExists($tempDirectory);

        $utility->delete();

        $this->assertFalse($utility->exists());
    }

    public function testUtilityCanCreateFileIfItDoesNotExist(): void
    {
        $tempFile = $this->tempFileName();

        $utility = $this->utility($tempFile);

        $this->assertFalse($utility->exists());

        $utility->touch();

        $this->assertTrue($utility->exists());

        $this->assertTrue(is_file($tempFile));

        $utility->delete();

        $this->assertFalse($utility->exists());
    }
}
