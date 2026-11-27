<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;

final class SetContentTest extends BaseTestSuite
{
    public function testThrowsErrorIfFileDoesNotExistWhenAppending(): void
    {
        $tempFile = $this->tempFileName();

        $utility = $this->utility($tempFile);
        $this->expectException(FileNotFoundException::class);
        $utility->setContent('hello');
    }

    public function testUtilitySetsContentOfFile(): void
    {
        $tempFile = $this->makeTempFile();

        $utility = $this->utility($tempFile);
        $this->assertSame('hello', $utility->setContent('hello')->content());
        $this->assertSame('world', $utility->setContent('world')->content());
    }

    public function testSetContentKeepsPermissionsAndLeavesNoTempFiles(): void
    {
        $directory = $this->makeTempDirectory();
        $file = $directory . DIRECTORY_SEPARATOR . 'Rupert.txt';
        file_put_contents($file, 'Gerald');
        chmod($file, 0640);

        $this->utility($file)->setContent('Arthur');

        $this->assertSame('Arthur', file_get_contents($file));
        $this->assertSame('0640', substr(sprintf('%o', fileperms($file)), -4));
        $this->assertSame(['Rupert.txt'], array_values(array_diff(scandir($directory), ['.', '..'])));

        unlink($file);
        rmdir($directory);
    }

    public function testSetContentWritesThroughASymlink(): void
    {
        $directory = $this->makeTempDirectory();
        $file = $directory . DIRECTORY_SEPARATOR . 'Gerald.txt';
        $link = $directory . DIRECTORY_SEPARATOR . 'Rupert.txt';
        file_put_contents($file, 'old');
        symlink($file, $link);

        $this->utility($link)->setContent('Arthur');

        $this->assertTrue(is_link($link));
        $this->assertSame('Arthur', file_get_contents($file));

        unlink($link);
        unlink($file);
        rmdir($directory);
    }
}
