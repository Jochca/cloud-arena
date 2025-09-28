<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;

final class DummyTest extends TestCase
{
    public function testAlwaysTrue(): void
    {
        // @phpstan-ignore-next-line
        $this->assertTrue(true);
    }
}
