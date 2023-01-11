<?php

namespace App\Shared\Domain\Bus\Event;

use App\Shared\Domain\DomainMessage;

interface EventBusInterface
{
    /**
     * @param DomainMessage[] $domainMessages
     */
    public function dispatch(array $domainMessages): void;
}

