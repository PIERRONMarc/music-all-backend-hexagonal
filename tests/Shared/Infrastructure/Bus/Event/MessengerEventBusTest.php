<?php

namespace App\Tests\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\EventBusInterface;
use App\Shared\Domain\DomainMessage;
use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Domain\Serializer\SerializableInterface;
use App\Shared\Infrastructure\Bus\Event\MessengerEventBus;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerEventBusTest extends TestCase
{
    private MessageBusInterface $symfonyMessageBus;

    private EventBusInterface $eventBus;

    public function setUp(): void
    {
        $this->symfonyMessageBus = $this->getSymfonyEventBusStub();
        $this->eventBus = new MessengerEventBus($this->symfonyMessageBus);
    }

    public function testDomainMessagesAreDispatched(): void
    {
        $domainMessages = $this->getDummyDomainMessage();
        $this->eventBus->dispatch($domainMessages);

        $this->assertSame($domainMessages[0], $this->symfonyMessageBus->lastDispatchedDomainMessage()); /** @phpstan-ignore-line */
    }

    private function getSymfonyEventBusStub(): MessageBusInterface
    {
        return new class() implements MessageBusInterface {
            private DomainMessage $dispatchedDomainMessage;

            public function dispatch($message, array $stamps = []): Envelope
            {
                $this->dispatchedDomainMessage = $message;

                return new Envelope($message);
            }

            public function lastDispatchedDomainMessage(): DomainMessage
            {
                return $this->dispatchedDomainMessage;
            }
        };
    }

    /**
     * @return DomainMessage[]
     */
    private function getDummyDomainMessage(): array
    {
        $event = new class() implements DomainEventInterface {
            public function serialize(): array
            {
                return [];
            }

            public static function deserialize(array $data): SerializableInterface
            {
                return new self();
            }
        };

        return [new DomainMessage(
            '1',
            1,
            $event,
            new DateTimeImmutable()
        )];
    }
}