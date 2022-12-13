<?php

namespace App\Shared\Infrastructure\Bus\Command;

use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerCommandBus implements CommandBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $symfonyMessageBus
    ) {}

    public function dispatch(CommandInterface $command): void
    {
        $this->symfonyMessageBus->dispatch($command);
    }
}