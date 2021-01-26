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
            new \Symfony\Component\Finder\SplFileInfo($this->resourceFilePath('RandomClassForTestingWith.php'), '', 'RandomClassForTestingWith.php'),
            new \Symfony\Component\Finder\SplFileInfo($this->resourceFilePath('RandomFileForTestingWith.js'), '', 'RandomFileForTestingWith.js'),
            new \Symfony\Component\Finder\SplFileInfo($this->resourceFilePath('RandomPHPFileForTestingWithoutNamespace.php'), '', 'RandomPHPFileForTestingWithoutNamespace.php'),
        ];
        $this->assertEquals($expectedResult, $helper->files());
    }

    /**
     * @covers ::files
     */
    public function testThrowsExceptionIfPathIsNotADirectory()
    {
        $helper = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));

        $this->expectException(NotADirectoryException::class);
        $helper->files();
    }

}
