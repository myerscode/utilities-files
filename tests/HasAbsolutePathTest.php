<?php

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Iterator;

class HasAbsolutePathTest extends BaseTestSuite
{
    public static function dataProvider(): Iterator
    {
        yield 'linux - file - Absolute 1' => ['/folder/file.txt', true];
        yield 'linux - folder - Absolute 1' => ['/folder/', true];
        yield 'linux - file - Not Absolute 1' => ['~/folder/file.txt', false];
        yield 'linux - file - Not Absolute 2' => ['../folder/file.txt', false];
        yield 'windows - file - Absolute 1' => ['C:\folder\file.txt', true];
        yield 'windows - file - Absolute 2' => ['C:\\folder\\file.txt', true];
        yield 'windows - folder - Absolute 1' => ['C:\folder', true];
        yield 'windows - folder - Absolute 2' => ['C:\\folder', true];
    }

    #[DataProvider('dataProvider')]
    public function testCanAssertPathIsAbsolute(string $path, bool $isAbsolute): void
    {
        $this->assertEquals($isAbsolute, $this->utility($path)->hasAbsolutePath());
    }
}
