<?php

namespace App\Shared\Domain;

Interface RandomGuestNameGeneratorInterface
{
    public function generate(): string;
}