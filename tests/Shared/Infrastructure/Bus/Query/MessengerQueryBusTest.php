<?php

namespace App\Tests\Shared\Infrastructure\Bus\Query;

use App\Shared\Domain\Bus\Query\QueryBusInterface;
use App\Shared\Domain\Bus\Query\QueryInterface;
use App\Shared\Infrastructure\Bus\Query\MessengerQueryBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class MessengerQueryBusTest extends TestCase
{
    private MessageBusInterface $symfonyMessageBus;

    private QueryBusInterface $queryBus;

    public function setUp(): void
    {
        $this->symfonyMessageBus = $this->getSymfonyMessageBusStub();
        $this->queryBus = new MessengerQueryBus($this->symfonyMessageBus);
    }

    public function testCommandIsDispatched(): void
    {
        $query = $this->getDummyQuery();
        $this->queryBus->dispatch($query);

        $this->assertSame($query, $this->symfonyMessageBus->lastDispatchedQuery()); /** @phpstan-ignore-line */
    }

    private function getSymfonyMessageBusStub(): MessageBusInterface
    {
        return new class() implements MessageBusInterface {
            private QueryInterface $dispatchedQuery;

            public function dispatch($message, array $stamps = []): Envelope
            {
                $this->dispatchedQuery = $message;

                return new Envelope($message, [new HandledStamp(true, 'dummyHandler')]);
            }

            public function lastDispatchedQuery(): QueryInterface
            {
                return $this->dispatchedQuery;
            }
        };
    }

    private function getDummyQuery(): QueryInterface
    {
        return new class() implements QueryInterface {};
    }
}