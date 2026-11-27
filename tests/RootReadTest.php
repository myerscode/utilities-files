<?php

declare(strict_types=1);

namespace Tests;

use InvalidArgumentException;
use Myerscode\Utilities\Files\Utility;
use PHPUnit\Framework\Attributes\DataProvider;

final class RootReadTest extends BaseTestSuite
{
    private string $outside = '';
    private string $root = '';

    protected function setUp(): void
    {
        $this->root = $this->makeTempDirectory();
        $this->outside = $this->makeTempDirectory();
        file_put_contents($this->root . '/Rupert.php', "<?php\nnamespace Gerald;\n");
        file_put_contents($this->outside . '/Arthur.php', "<?php\nnamespace Gerald;\n");
    }

    protected function tearDown(): void
    {
        foreach ([$this->root, $this->outside] as $directory) {
            array_map(unlink(...), glob($directory . '/*'));
            rmdir($directory);
        }
    }

    /**
     * @return array<string, array{string}>
     */
    public static function reads(): array
    {
        return [['content'], ['namespace'], ['lines'], ['files']];
    }

    #[DataProvider('reads')]
    public function testReadOutsideRootIsRefused(string $method): void
    {
        $path = $method === 'files' ? $this->outside : $this->outside . '/Arthur.php';

        $this->expectException(InvalidArgumentException::class);
        (new Utility($path, $this->root))->{$method}();
    }

    #[DataProvider('reads')]
    public function testReadOutsideRootViaTraversalIsRefused(string $method): void
    {
        $path = $this->root . '/../' . basename($this->outside) . '/Arthur.php';

        $this->expectException(InvalidArgumentException::class);
        (new Utility($path, $this->root))->{$method}();
    }

    public function testReadsInsideRootWork(): void
    {
        $file = new Utility($this->root . '/Rupert.php', $this->root);

        $this->assertStringContainsString('Gerald', $file->content());
        $this->assertSame('Gerald', $file->namespace());
        $this->assertSame('namespace Gerald;', iterator_to_array($file->lines(), false)[1]);
        $this->assertCount(1, (new Utility($this->root, $this->root))->files());
    }
}
