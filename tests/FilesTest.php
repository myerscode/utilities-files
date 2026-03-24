<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\NotADirectoryException;
use Symfony\Component\Finder\SplFileInfo;

final class FilesTest extends BaseTestSuite
{
    public function testThrowsExceptionIfPathIsNotADirectory(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));

        $this->expectException(NotADirectoryException::class);
        $utility->files();
    }

    public function testUtilityCanFilesInADirectory(): void
    {
        $utility = $this->utility(__DIR__ . '/Resources/');
        $expectedResult = [
            new SplFileInfo($this->resourceFilePath('RandomClassForTestingWith.php'), '', 'RandomClassForTestingWith.php'),
            new SplFileInfo($this->resourceFilePath('RandomFileForTestingWith.js'), '', 'RandomFileForTestingWith.js'),
            new SplFileInfo(
                $this->resourceFilePath('RandomPHPFileForTestingWithoutNamespace.php'),
                '',
                'RandomPHPFileForTestingWithoutNamespace.php',
            ),
        ];
        $this->assertEquals($expectedResult, $utility->files());
    }
}
