<?php

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\EventBusInterface;
use App\Shared\Domain\Bus\Event\EventListenerInterface;
use App\Shared\Domain\DomainMessage;

class EventBus implements EventBusInterface
{
    public function subscribe(EventListenerInterface $eventListener): void
    {
        // TODO: Implement subscribe() method.
    }

    /**
     * @param DomainMessage[] $domainMessages
     */
    public function publish(array $domainMessages): void
    {
        // TODO: Implement publish() method.
    }
}