<?php

namespace Tests;

use Myerscode\Utilities\Files\Utility;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class ConstructTest extends BaseTestSuite
{

    public function testUtilityAcceptsValidPaths()
    {
        $tempName = $this->tempFileName();
        $helper = $this->utility($tempName);
        $this->assertEquals($tempName, $helper->path());

        $tempName = $this->tempDirectoryName();
        $helper = $this->utility($tempName);
        $this->assertEquals($tempName, $helper->path());
    }

    public function testUtilityCanMakeItselfStatically()
    {
        $tempName = $this->tempFileName();
        $helper = Utility::make($tempName);
        $this->assertEquals($tempName, $helper->path());

        $tempName = $this->tempDirectoryName();
        $helper = Utility::make($tempName);
        $this->assertEquals($tempName, $helper->path());
    }
}
