<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Utilities\Files\Utility;

final class StaticFactoryTest extends BaseTestSuite
{
    public function testChainedCallsKeepTheSubclass(): void
    {
        $subclass = new class ('Rupert.txt') extends Utility {
        };
        $file = $this->makeTempFile();
        $utility = new $subclass($file);

        $this->assertInstanceOf($subclass::class, $utility->setContent('Gerald'));
        $this->assertInstanceOf($subclass::class, $utility->appendContent('Arthur'));
        $this->assertInstanceOf($subclass::class, $utility->touch());

        unlink($file);
    }
}
