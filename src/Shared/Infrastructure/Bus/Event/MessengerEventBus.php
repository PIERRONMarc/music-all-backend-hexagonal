<?php

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\EventBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerEventBus implements EventBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $symfonyMessageBus
    ) {}

    public function dispatch(array $domainMessages): void
    {
        foreach ($domainMessages as $domainMessage) {
            $this->symfonyMessageBus->dispatch($domainMessage);
        }
    }
}