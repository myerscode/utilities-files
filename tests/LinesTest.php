<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;

final class LinesTest extends BaseTestSuite
{
    public function testYieldsEachLineWithoutLineEndings(): void
    {
        $file = $this->tempFileName();
        file_put_contents($file, "Rupert\r\nGerald\n\nArthur");

        try {
            $this->assertSame(['Rupert', 'Gerald', '', 'Arthur'], iterator_to_array($this->utility($file)->lines(), false));
        } finally {
            unlink($file);
        }
    }

    public function testStreamsLazily(): void
    {
        $file = $this->tempFileName();
        file_put_contents($file, "Rupert\nGerald\n");

        try {
            $lines = $this->utility($file)->lines();
            $this->assertSame('Rupert', $lines->current());
            $lines->next();
            $this->assertSame('Gerald', $lines->current());
        } finally {
            unlink($file);
        }
    }

    public function testEmptyFileYieldsNothing(): void
    {
        $file = $this->makeTempFile();

        try {
            $this->assertSame([], iterator_to_array($this->utility($file)->lines()));
        } finally {
            unlink($file);
        }
    }

    public function testThrowsWhenFileDoesNotExistBeforeIterating(): void
    {
        $this->expectException(FileNotFoundException::class);
        $this->utility($this->tempFileName())->lines();
    }
}
