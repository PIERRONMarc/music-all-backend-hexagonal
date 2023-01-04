<?php

namespace App\Shared\Domain\Bus\Event;

use App\Shared\Domain\Event\DomainEventInterface;

/**
 * Handles dispatched events.
 */
interface EventListener
{
    public function handle(DomainEventInterface $domainEvent): void;
}