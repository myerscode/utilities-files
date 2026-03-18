<?php

declare(strict_types=1);

namespace Tests;

final class PathTest extends BaseTestSuite
{
    public function testUtilityAcceptsValidPaths(): void
    {
        $tempName = $this->tempFileName();
        $utility = $this->utility($tempName);
        $this->assertSame($tempName, $utility->path());
    }
}
