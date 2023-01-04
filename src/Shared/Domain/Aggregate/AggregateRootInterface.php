<?php

namespace App\Shared\Domain\Aggregate;

use App\Shared\Domain\DomainMessage;

interface AggregateRootInterface
{
    public function getId(): string;

    /**
     * @return DomainMessage[]
     */
    public function getUncommittedEvents(): array;
}