<?php

namespace Tests;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class NameTest extends BaseTestSuite
{

    public function testUtilityCanGuessTheFileName()
    {
        $helper = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertEquals('RandomClassForTestingWith', $helper->name());
    }

    public function testUtilityCanGuessTheFileNameOfADirectory()
    {
        $helper = $this->utility($this->resourceFilePath(''));
        $this->assertEquals('Resources', $helper->name());
    }

    public function testUtilityCanGuessTheFileNameOfADirectoryWhichHasNoExtension()
    {
        $helper = $this->utility($this->resourceFilePath(''));
        $this->assertEquals('Resources', $helper->name(true));
    }

    public function testUtilityCanGuessTheFileNameOfADirectoryWhichHasExtension()
    {
        $helper = $this->utility($this->resourceFilePath('Test.dir'));
        $this->assertEquals('Test.dir', $helper->name(true));
    }

    public function testUtilityCanGuessTheFileNameWithExtension()
    {
        $helper = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertEquals('RandomClassForTestingWith.php', $helper->name(true));
    }
}
