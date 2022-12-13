<?php

namespace App\Shared\Domain;

interface RandomRoomNameGeneratorInterface
{
    public function generate(): string;
}