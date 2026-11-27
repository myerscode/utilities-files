<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Myerscode\Utilities\Files\Exceptions\FileFormatException;
use Myerscode\Utilities\Files\Exceptions\FileNotFoundException;
use Myerscode\Utilities\Files\Exceptions\InvalidFileTypeException;

final class NamespaceTest extends BaseTestSuite
{
    /**
     * @return array<string, array{string, string}>
     */
    public static function namespaceSources(): array
    {
        return [
            'braces' => ["<?php\nnamespace Rupert\\Gerald {\n class A {}\n}\n", 'Rupert\\Gerald'],
            'tab' => ["<?php\nnamespace\tRupert;\n", 'Rupert'],
            'crlf' => ["<?php\r\nnamespace Rupert\\Arthur;\r\n", 'Rupert\\Arthur'],
            'no space before brace' => ["<?php\nnamespace Rupert{}\n", 'Rupert'],
            'indented' => ["<?php\n    namespace Rupert;\n", 'Rupert'],
            'after comments' => ["<?php\n// namespace Gerald;\n/* namespace Arthur; */\nnamespace Rupert;\n", 'Rupert'],
            'comment between' => ["<?php\nnamespace /* x */ Rupert;\n", 'Rupert'],
            'inside docblock' => ["<?php\n/**\n * namespace Gerald;\n */\nnamespace Rupert;\n", 'Rupert'],
        ];
    }

    public function testMissingNamespaceIsStillCatchableAsTheDeprecatedMisspeltException(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomPHPFileForTestingWithoutNamespace.php'));

        try {
            $utility->namespace();
            $this->fail('Expected an exception');
        } catch (\Myerscode\Utilities\Files\Exceptions\FileFormatExpection $exception) {
            $this->assertInstanceOf(FileFormatException::class, $exception);
        }
    }
    public function testUtilityCanFindPHPNamespace(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomClassForTestingWith.php'));
        $this->assertSame('Tests\Resources', $utility->namespace());
    }

    #[DataProvider('namespaceSources')]
    public function testUtilityFindsNamespaceInDifferentFormats(string $source, string $expected): void
    {
        $file = $this->tempFileName('.php');
        file_put_contents($file, $source);

        try {
            $this->assertSame($expected, $this->utility($file)->namespace());
        } finally {
            unlink($file);
        }
    }

    public function testUtilityIgnoresNamespaceOperatorAndComments(): void
    {
        $file = $this->tempFileName('.php');
        file_put_contents($file, "<?php\n// namespace Gerald;\n\$a = namespace\\Rupert\\foo();\n");

        try {
            $this->expectException(FileFormatException::class);
            $this->utility($file)->namespace();
        } finally {
            unlink($file);
        }
    }

    public function testUtilityThrowsErrorIfFileDoesNotHaveANamespace(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomPHPFileForTestingWithoutNamespace.php'));
        $this->expectException(FileFormatException::class);
        $utility->namespace();
    }

    public function testUtilityThrowsErrorIfFileIsNotFound(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomFileForTestingWith.whoops'));
        $this->expectException(FileNotFoundException::class);
        $utility->namespace();
    }

    public function testUtilityThrowsErrorIfFileIsNotPHP(): void
    {
        $utility = $this->utility($this->resourceFilePath('RandomFileForTestingWith.js'));
        $this->expectException(InvalidFileTypeException::class);
        $utility->namespace();
    }
}
