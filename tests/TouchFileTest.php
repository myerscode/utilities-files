<?php

declare(strict_types=1);

namespace Tests;

final class TouchFileTest extends BaseTestSuite
{
    public function testUtilityCanCreateFileIfItDoesNotExist(): void
    {
        $tempFile = $this->tempFileName();

        $utility = $this->utility($tempFile);

        $this->assertFalse($utility->exists());

        $utility->touchFile();

        $this->assertTrue($utility->exists());

        $this->assertTrue(is_file($tempFile));

        $utility->delete();

        $this->assertFalse($utility->exists());
    }
}
