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
    public function testUtilityAcceptsValidPaths()
    {
        $tempName = $this->tempFileName();
        $helper = $this->utility($tempName);
        $this->assertEquals($tempName, $helper->path());
    }
}
