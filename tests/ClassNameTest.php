<?php

namespace Tests;

class ClassNameTest extends BaseTestSuite
{
    public function testUtilityGetsClassNameFromFileName(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertEquals('RandomClassForTestingWith', $utility->className());
    }
}
