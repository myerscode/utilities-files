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
}
