<?php

namespace Tests;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class PathTest extends BaseTestSuite
{
    /**
     * @covers ::path
     */
    public function testUtilityAcceptsValidPaths(): void
    {
        $tempName = $this->tempFileName();
        $utility = $this->utility($tempName);
        $this->assertEquals($tempName, $utility->path());
    }
}
