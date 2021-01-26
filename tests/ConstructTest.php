<?php

namespace Tests;

use Myerscode\Utilities\Files\Utility;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class ConstructTest extends BaseTestSuite
{

    /**
     * @covers ::__construct
     */
    public function testUtilityAcceptsValidPaths()
    {
        $tempName = $this->tempFileName();
        $helper = $this->utility($tempName);
        $this->assertEquals($tempName, $helper->path());

        $tempName = $this->tempDirectoryName();
        $helper = $this->utility($tempName);
        $this->assertEquals($tempName, $helper->path());
    }

    /**
     * @covers ::make
     */
    public function testUtilityCanMake()
    {
        $tempName = $this->tempFileName();
        $helper = Utility::make($tempName);
        $this->assertEquals($tempName, $helper->path());

        $tempName = $this->tempDirectoryName();
        $helper = Utility::make($tempName);
        $this->assertEquals($tempName, $helper->path());
    }
}
