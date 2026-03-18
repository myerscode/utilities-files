<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Utilities\Files\Utility;

final class ConstructTest extends BaseTestSuite
{
    public function testUtilityAcceptsValidPaths(): void
    {
        $tempName = $this->tempFileName();
        $helper = $this->utility($tempName);
        $this->assertSame($tempName, $helper->path());

        $tempName = $this->tempDirectoryName();
        $helper = $this->utility($tempName);
        $this->assertSame($tempName, $helper->path());
    }

    public function testUtilityCanMakeItselfStatically(): void
    {
        $tempName = $this->tempFileName();
        $helper = Utility::make($tempName);
        $this->assertSame($tempName, $helper->path());

        $tempName = $this->tempDirectoryName();
        $helper = Utility::make($tempName);
        $this->assertSame($tempName, $helper->path());
    }
}
