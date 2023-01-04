<?php

namespace App\Shared\Domain\EventSourcing;

use App\Shared\Domain\Aggregate\AggregateRootInterface;
use App\Shared\Domain\Bus\Event\EventBusInterface;

abstract class AbstractEventSourcingRepository
{
    public function __construct(
        private readonly EventStoreInterface $eventStore,
        private readonly EventBusInterface $eventBus
    ) {}
    public function save(AggregateRootInterface $aggregateRoot): void
    {
        $domainEventStream = $aggregateRoot->getUncommittedEvents();
        $this->eventStore->append($aggregateRoot->getId(), $domainEventStream);
        $this->eventBus->publish($domainEventStream);
    }

}