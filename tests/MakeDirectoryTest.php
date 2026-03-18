<?php

declare(strict_types=1);

namespace Tests;

final class MakeDirectoryTest extends BaseTestSuite
{
    public function testUtilityCanCreateDirectoryIfItDoesNotExist(): void
    {
        $tempDirectory = $this->tempDirectoryName();

        $utility = $this->utility($tempDirectory);

        $this->assertFalse($utility->exists());

        $utility->makeDirectory();

        $this->assertTrue($utility->exists());

        $this->assertDirectoryExists($tempDirectory);

        $utility->delete();

        $this->assertFalse($utility->exists());
    }
}
