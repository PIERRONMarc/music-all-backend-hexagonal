<?php

namespace App\Shared\Infrastructure\RandomRoomNameGenerator;


use App\Shared\Domain\RandomRoomNameGeneratorInterface;
use App\Shared\Infrastructure\Randomizer\RandomizerInterface;

class RandomRoomNameGenerator implements RandomRoomNameGeneratorInterface
{
    private RandomizerInterface $randomizer;

    /**
     * @var string[]
     */
    private array $venues;

    public function __construct(RandomizerInterface $randomizer)
    {
        $this->randomizer = $randomizer;
        $this->venues = file(__DIR__.'/venues.txt', \FILE_IGNORE_NEW_LINES);
    }

    /**
     * Get a randomly generated name.
     */
    public function generate(): string
    {
        return $this->venues[$this->randomizer->getInt(0, \count($this->venues) - 1)];
    }
}