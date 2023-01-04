<?php

namespace App\Shared\Domain\Bus\Event;

use App\Shared\Domain\DomainMessage;

/**
 * Publishes events to the subscribed event listeners.
 */
interface EventBusInterface
{
    /**
     * Subscribes the event listener to the event bus.
     */
    public function subscribe(EventListenerInterface $eventListener): void;

    /**
     * Publishes the events from the domain event stream to the listeners.
     *
     * @param DomainMessage[] $domainMessages
     */
    public function publish(array $domainMessages): void;
}

