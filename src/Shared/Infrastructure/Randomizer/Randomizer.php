<?php

namespace App\Shared\Infrastructure\Randomizer;

class Randomizer implements RandomizerInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInt(int $min, int $max): int
    {
        return mt_rand($min, $max);
    }
}
