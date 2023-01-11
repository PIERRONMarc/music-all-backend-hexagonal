<?php

namespace App\Shared\Infrastructure\RandomGuestNameGenerator;

use App\Shared\Domain\RandomGuestNameGeneratorInterface;
use App\Shared\Infrastructure\Randomizer\RandomizerInterface;

class RandomGuestNameGenerator implements RandomGuestNameGeneratorInterface
{
    /**
     * @var string[]
     */
    private array $adjectives;

    /**
     * @var string[]
     */
    private array $nouns;

    private RandomizerInterface $randomizer;

    public function __construct(RandomizerInterface $randomizer)
    {
        $this->randomizer = $randomizer;
        $this->adjectives = file(__DIR__.'/adjectives.txt', \FILE_IGNORE_NEW_LINES);
        $this->nouns = file(__DIR__.'/nouns.txt', \FILE_IGNORE_NEW_LINES);
    }

    public function generate(): string
    {
        $adjective = $this->adjectives[$this->randomizer->getInt(0, \count($this->adjectives) - 1)];
        $noun = $this->nouns[$this->randomizer->getInt(0, \count($this->nouns) - 1)];

        return ucwords("{$adjective} {$noun}");
    }
}