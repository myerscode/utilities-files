<?php

namespace Tests;

class ExtensionTest extends BaseTestSuite
{
    public function testUtilityCanGuessTheFileExtension(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertEquals('php', $utility->extension());
    }
}
