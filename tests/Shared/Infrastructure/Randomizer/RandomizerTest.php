<?php

namespace App\Tests\Shared\Infrastructure\Randomizer;

use App\Shared\Infrastructure\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class RandomizerTest extends TestCase
{
    public function testRandIsSuccessful(): void
    {
        $randomizer = new Randomizer();
        $this->assertSame(0, $randomizer->getInt(0, 0));
    }
}