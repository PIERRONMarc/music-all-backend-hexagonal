<?php

namespace App\Shared\Domain\Aggregate;

use App\Shared\Domain\DomainMessage;
use App\Shared\Domain\Event\DomainEventInterface;
use DateTimeImmutable;

abstract class AbstractEventSourcedAggregateRoot implements AggregateRootInterface
{
    private string $id;

    private int $playhead = -1; // 0-based playhead allows events[0] to contain playhead 0

    /**
     * @var DomainMessage[]
     */
    private array $uncommittedEvents = [];

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return DomainMessage[]
     */
    public function getUncommittedEvents(): array
    {
        return $this->uncommittedEvents;
    }

    /**
     * Applies an event. The event is added to the AggregateRoot's list of uncommitted events.
     *
     * @param DomainEventInterface $domainEvent
     */
    public function apply(DomainEventInterface $domainEvent): void
    {
        $this->handle($domainEvent);

        ++$this->playhead;
        $this->uncommittedEvents[] = new DomainMessage(
            $this->id,
            $this->playhead,
            $domainEvent,
            new DateTimeImmutable()
        );
    }

    /**
     * Handles event if capable.
     *
     * @param DomainEventInterface $event
     */
    protected function handle(DomainEventInterface $event): void
    {
        $method = $this->getApplyMethod($event);

        if (!method_exists($this, $method)) {
            return;
        }

        $this->$method($event);
    }

    private function getApplyMethod(DomainEventInterface $event): string
    {
        $classParts = explode('\\', get_class($event));

        return 'apply'.end($classParts);
    }

    /**
     * @param DomainEventInterface[] $domainEventStream
     */
    public function initializeState(array $domainEventStream): void
    {
        foreach ($domainEventStream as $domainEvent) {
            ++$this->playhead;
            $this->handle($domainEvent);
        }
    }
}