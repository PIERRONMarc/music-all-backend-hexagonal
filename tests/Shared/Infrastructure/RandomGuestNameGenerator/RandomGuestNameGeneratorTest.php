<?php

namespace App\Tests\Shared\Infrastructure\RandomGuestNameGenerator;

use App\Shared\Infrastructure\RandomGuestNameGenerator\RandomGuestNameGenerator;
use App\Shared\Infrastructure\Randomizer\RandomizerInterface;
use PHPUnit\Framework\TestCase;

class RandomGuestNameGeneratorTest extends TestCase
{
    public function testGeneratorReturnCorrectName(): void
    {
        $randomizer = $this->createMock(RandomizerInterface::class);
        $randomizer->method('getInt')->willReturn(0);

        $generator = new RandomGuestNameGenerator($randomizer);

        $this->assertSame('Adorable Aardvark', $generator->generate());
    }
}