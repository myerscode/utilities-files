<?php

namespace Tests;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class ClassNameTest extends BaseTestSuite
{

    /**
     * @covers ::className
     */
    public function testUtilityGetsClassNameFromFileName()
    {

        $helper = $this->utility(__DIR__.'/Resources/RandomClassForTestingWith.php');
        $this->assertEquals('RandomClassForTestingWith', $helper->className());
    }

}
