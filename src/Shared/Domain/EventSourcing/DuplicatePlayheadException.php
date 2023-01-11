<?php

namespace App\Shared\Domain\EventSourcing;

use App\Shared\Domain\DomainMessage;
use App\Shared\Domain\Event\DomainEventInterface;
use Exception;

/**
 * Exceptions thrown by event store implementations.
 */
final class DuplicatePlayheadException extends EventStoreException
{
    /**
     * @var DomainMessage[]
     */
    private array $eventStream;

    /**
     * @param DomainMessage[] $eventStream
     * @param Exception $previous
     */
    public function __construct(array $eventStream, $previous = null)
    {
        parent::__construct('', 0, $previous);

        $this->eventStream = $eventStream;
    }

    /**
     * @return DomainMessage[]
     */
    public function getEventStream(): array
    {
        return $this->eventStream;
    }
}