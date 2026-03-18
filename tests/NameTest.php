<?php

declare(strict_types=1);

namespace Tests;

final class NameTest extends BaseTestSuite
{
    public function testUtilityCanGuessTheFileName(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertSame('RandomClassForTestingWith', $utility->name());
    }

    public function testUtilityCanGuessTheFileNameOfADirectory(): void
    {
        $utility = $this->utility($this->resourceFilePath(''));
        $this->assertSame('Resources', $utility->name());
    }

    public function testUtilityCanGuessTheFileNameOfADirectoryWhichHasExtension(): void
    {
        $utility = $this->utility($this->resourceFilePath('Test.dir'));
        $this->assertSame('Test.dir', $utility->name(true));
    }

    public function testUtilityCanGuessTheFileNameOfADirectoryWhichHasNoExtension(): void
    {
        $utility = $this->utility($this->resourceFilePath(''));
        $this->assertSame('Resources', $utility->name(true));
    }

    public function testUtilityCanGuessTheFileNameWithExtension(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertSame('RandomClassForTestingWith.php', $utility->name(true));
    }
}
