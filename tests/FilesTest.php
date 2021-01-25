<?php

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\NotADirectoryException;

/**
 * @coversDefaultClass \Myerscode\Utilities\Files\Utility
 */
class FilesTest extends BaseTestSuite
{

    /**
     * @covers ::files
     */
    public function testUtilityCanFilesInADirectory()
    {

        $helper = $this->utility(__DIR__.'/Resources/');
        $expectedResult = [
            new \Symfony\Component\Finder\SplFileInfo(__DIR__.'/Resources/RandomFileForTestingWith.js', '', 'RandomFileForTestingWith.js'),
            new \Symfony\Component\Finder\SplFileInfo(__DIR__.'/Resources/RandomClassForTestingWith.php', '', 'RandomClassForTestingWith.php'),
        ];
        $this->assertEquals($expectedResult, $helper->files());
    }

    /**
     * @covers ::files
     */
    public function testThrowsExceptionIfPathIsNotADirectory()
    {

        $helper = $this->utility(__DIR__.'/Resources/RandomClassForTestingWith.php');

        $this->expectException(NotADirectoryException::class);
        $helper->files();
    }

}
