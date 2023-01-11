<?php

namespace App\Tests\Shared\Infrastructure\RandomRoomNameGenerator;

use App\Shared\Infrastructure\Randomizer\RandomizerInterface;
use App\Shared\Infrastructure\RandomRoomNameGenerator\RandomRoomNameGenerator;
use PHPUnit\Framework\TestCase;

class RandomRoomNameGeneratorTest extends TestCase
{
    public function testGeneratorReturnCorrectVenue(): void
    {
        $randomizer = $this->createMock(RandomizerInterface::class);
        $randomizer->method('getInt')->willReturn(0);

        $generator = new RandomRoomNameGenerator($randomizer);

        $this->assertSame('Red Rocks', $generator->generate());
    }
}