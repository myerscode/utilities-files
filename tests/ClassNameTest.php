<?php

declare(strict_types=1);

namespace Tests;

final class ClassNameTest extends BaseTestSuite
{
    public function testUtilityGetsClassNameFromFileName(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertSame('RandomClassForTestingWith', $utility->className());
    }

    public function testClassNameAndNameSplitExtensionAtTheLastDot(): void
    {
        $utility = $this->utility('src/Rupert.test.php');

        $this->assertSame('Rupert.test', $utility->className());
        $this->assertSame($utility->name(), $utility->className());
    }
}
