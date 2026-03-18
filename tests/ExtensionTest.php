<?php

declare(strict_types=1);

namespace Tests;

final class ExtensionTest extends BaseTestSuite
{
    public function testUtilityCanGuessTheFileExtension(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertSame('php', $utility->extension());
    }
}
