<?php

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\DomainMessage;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EventHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    public function __invoke(DomainMessage $domainMessage): void
    {
        $this->eventDispatcher->dispatch($domainMessage->getPayload());
    }
}