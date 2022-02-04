<?php

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\NotADirectoryException;
use Symfony\Component\Finder\SplFileInfo;

class FilesTest extends BaseTestSuite
{
    public function testThrowsExceptionIfPathIsNotADirectory(): void
    {
        $helper = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));

        $this->expectException(NotADirectoryException::class);
        $helper->files();
    }

    public function testUtilityCanFilesInADirectory(): void
    {
        $helper = $this->utility(__DIR__ . '/Resources/');
        $expectedResult = [
            new SplFileInfo($this->resourceFilePath('RandomClassForTestingWith.php'), '', 'RandomClassForTestingWith.php'),
            new SplFileInfo($this->resourceFilePath('RandomFileForTestingWith.js'), '', 'RandomFileForTestingWith.js'),
            new SplFileInfo(
                $this->resourceFilePath('RandomPHPFileForTestingWithoutNamespace.php'),
                '',
                'RandomPHPFileForTestingWithoutNamespace.php'
            ),
        ];
        $this->assertEquals($expectedResult, $helper->files());
    }
}
