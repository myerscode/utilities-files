<?php

namespace Tests;

class NameTest extends BaseTestSuite
{
    public function testUtilityCanGuessTheFileName(): void
    {
        $helper = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertEquals('RandomClassForTestingWith', $helper->name());
    }

    public function testUtilityCanGuessTheFileNameOfADirectory(): void
    {
        $helper = $this->utility($this->resourceFilePath(''));
        $this->assertEquals('Resources', $helper->name());
    }

    public function testUtilityCanGuessTheFileNameOfADirectoryWhichHasExtension(): void
    {
        $helper = $this->utility($this->resourceFilePath('Test.dir'));
        $this->assertEquals('Test.dir', $helper->name(true));
    }

    public function testUtilityCanGuessTheFileNameOfADirectoryWhichHasNoExtension(): void
    {
        $helper = $this->utility($this->resourceFilePath(''));
        $this->assertEquals('Resources', $helper->name(true));
    }

    public function testUtilityCanGuessTheFileNameWithExtension(): void
    {
        $helper = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertEquals('RandomClassForTestingWith.php', $helper->name(true));
    }
}
