<?php

declare(strict_types=1);

namespace Tests;

use InvalidArgumentException;
use Myerscode\Utilities\Files\Exceptions\FileFormatException;
use Myerscode\Utilities\Files\Utility;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresOperatingSystem;
use RuntimeException;

final class FailurePathsTest extends BaseTestSuite
{
    private string $directory = '';

    protected function setUp(): void
    {
        $this->directory = $this->makeTempDirectory();
    }

    protected function tearDown(): void
    {
        chmod($this->directory, 0755);

        foreach (glob($this->directory . '/{,.}*', GLOB_BRACE) ?: [] as $entry) {
            if (is_file($entry)) {
                chmod($entry, 0644);
                unlink($entry);
            }
        }

        rmdir($this->directory);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function streamWrappers(): array
    {
        return [['phar://Rupert.phar/Gerald.php'], ['php://memory'], ['data://text/plain,Arthur'], ['http://example.com/Rupert']];
    }

    #[RequiresOperatingSystem('^(Linux|Dar)')]
    public function testAppendToReadOnlyFileThrowsAndKeepsContent(): void
    {
        $this->requireNonRoot();
        $file = $this->directory . '/Rupert.txt';
        file_put_contents($file, 'Gerald');
        chmod($file, 0444);

        try {
            $this->quietly(fn () => (new Utility($file))->appendContent('Arthur'));
            $this->fail('Expected a RuntimeException');
        } catch (RuntimeException) {
            $this->assertSame('Gerald', file_get_contents($file));
        }
    }

    #[RequiresOperatingSystem('^(Linux|Dar)')]
    public function testContentOfUnreadableFileThrows(): void
    {
        $this->requireNonRoot();
        $file = $this->directory . '/Rupert.txt';
        file_put_contents($file, 'Gerald');
        chmod($file, 0000);

        $this->expectException(RuntimeException::class);
        $this->quietly(fn () => (new Utility($file))->content());
    }

    public function testFileSchemeAndWindowsDrivePathsAreAccepted(): void
    {
        $this->assertSame('Rupert.txt', (new Utility('file:///tmp/Rupert.txt'))->name(true));
        $this->assertSame('Gerald.txt', (new Utility('C:/Arthur/Gerald.txt'))->name(true));
    }

    public function testGlobalNamespaceBlockHasNoNamespace(): void
    {
        $file = $this->directory . '/Rupert.php';
        file_put_contents($file, "<?php\nnamespace {\n    class Gerald {}\n}\n");

        $this->expectException(FileFormatException::class);
        (new Utility($file))->namespace();
    }

    #[RequiresOperatingSystem('^(Linux|Dar)')]
    public function testLinesOfUnreadableFileThrows(): void
    {
        $this->requireNonRoot();
        $file = $this->directory . '/Rupert.txt';
        file_put_contents($file, 'Gerald');
        chmod($file, 0000);

        $this->expectException(RuntimeException::class);
        (new Utility($file))->lines();
    }

    public function testNamespaceOperatorIsNotMistakenForADeclaration(): void
    {
        $file = $this->directory . '/Rupert.php';
        file_put_contents($file, "<?php\n\$x = namespace\\Arthur();\nnamespace Gerald;\n");

        $this->assertSame('Gerald', (new Utility($file))->namespace());
    }

    public function testPathWithNoExistingAncestorIsRefusedOutsideRoot(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new Utility('/Rupert/Gerald/Arthur.txt', $this->directory))->touchFile();
    }

    public function testRelativePathsAreConfinedToRelativeRoot(): void
    {
        file_put_contents($this->directory . '/Rupert.txt', 'Gerald');
        $previous = getcwd();
        chdir($this->directory);

        try {
            $this->assertSame('Gerald', (new Utility('Rupert.txt', '.'))->content());

            $this->expectException(InvalidArgumentException::class);
            (new Utility('../Rupert.txt', '.'))->content();
        } finally {
            chdir($previous);
        }
    }

    #[RequiresOperatingSystem('^(Linux|Dar)')]
    public function testSetContentInReadOnlyDirectoryThrowsAndKeepsContent(): void
    {
        $this->requireNonRoot();
        $file = $this->directory . '/Rupert.txt';
        file_put_contents($file, 'Gerald');
        chmod($this->directory, 0555);

        try {
            $this->quietly(fn () => (new Utility($file))->setContent('Arthur'));
            $this->fail('Expected a RuntimeException');
        } catch (RuntimeException) {
            $this->assertSame('Gerald', file_get_contents($file));
        } finally {
            chmod($this->directory, 0755);
        }

        $this->assertSame(['Rupert.txt'], array_values(array_diff(scandir($this->directory), ['.', '..'])));
    }

    #[DataProvider('streamWrappers')]
    public function testStreamWrapperPathsAreRejected(string $path): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Utility($path);
    }

    /**
     * Run a callback with PHP warnings from failing filesystem calls swallowed.
     */
    private function quietly(callable $callback): void
    {
        set_error_handler(static fn (): bool => true);

        try {
            $callback();
        } finally {
            restore_error_handler();
        }
    }

    private function requireNonRoot(): void
    {
        if (function_exists('posix_geteuid') && posix_geteuid() === 0) {
            $this->markTestSkipped('Root ignores file permissions.');
        }
    }
}
