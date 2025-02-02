<?php

namespace Tests;

class PathTest extends BaseTestSuite
{
    public function testUtilityAcceptsValidPaths(): void
    {
        $tempName = $this->tempFileName();
        $utility = $this->utility($tempName);
        $this->assertEquals($tempName, $utility->path());
    }
}
