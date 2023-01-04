<?php

namespace App\Shared\Domain\EventSourcing;

use App\Shared\Domain\DomainMessage;

interface EventStoreInterface
{
    /**
     * @return DomainMessage[]
     */
    public function load(string $id): array;

    /**
     * @param DomainMessage[] $eventStream
     */
    public function append(string $id, array $eventStream): void;
}