<?php

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\NotADirectoryException;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class ExtensionTest extends BaseTestSuite
{

    public function testUtilityCanGuessTheFileExtension()
    {
        $helper = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertEquals('php', $helper->extension());
    }
}
