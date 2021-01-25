<?php

namespace Tests;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class DeleteTest extends BaseTestSuite
{

    /**
     * @covers ::delete
     */
    public function testUtilityCanDeleteADirectory()
    {
        $tempDirectory = $this->tempDirectoryName();

        $helper = $this->utility($tempDirectory);

        $helper->touch();

        $this->assertTrue($helper->exists());

        $helper->delete();

        $this->assertFalse($helper->exists());
    }

    /**
     * @covers ::delete
     */
    public function testUtilityCanDeleteAFile()
    {
        $tempDirectory = $this->tempFileName();

        $helper = $this->utility($tempDirectory);

        $helper->touch();

        $this->assertTrue($helper->exists());

        $helper->delete();

        $this->assertFalse($helper->exists());
    }
}
