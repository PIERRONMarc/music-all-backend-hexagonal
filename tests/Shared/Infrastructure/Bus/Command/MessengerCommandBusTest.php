<?php

namespace App\Tests\Shared\Infrastructure\Bus\Command;

use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Command\CommandInterface;
use App\Shared\Infrastructure\Bus\Command\MessengerCommandBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerCommandBusTest extends TestCase
{
    private MessageBusInterface $symfonyMessageBus;

    private CommandBusInterface $commandBus;

    public function setUp(): void
    {
        $this->symfonyMessageBus = $this->getSymfonyMessageBusStub();
        $this->commandBus = new MessengerCommandBus($this->symfonyMessageBus);
    }

    public function testCommandIsDispatched(): void
    {
        $command = $this->getDummyCommand();
        $this->commandBus->dispatch($command);

        $this->assertSame($command, $this->symfonyMessageBus->lastDispatchedCommand()); /** @phpstan-ignore-line */
    }

    private function getSymfonyMessageBusStub(): MessageBusInterface
    {
        return new class() implements MessageBusInterface {
            private CommandInterface $dispatchedCommand;

            public function dispatch($message, array $stamps = []): Envelope
            {
                $this->dispatchedCommand = $message;

                return new Envelope($message);
            }

            public function lastDispatchedCommand(): CommandInterface
            {
                return $this->dispatchedCommand;
            }
        };
    }

    private function getDummyCommand(): CommandInterface
    {
        return new class() implements CommandInterface {};
    }
}