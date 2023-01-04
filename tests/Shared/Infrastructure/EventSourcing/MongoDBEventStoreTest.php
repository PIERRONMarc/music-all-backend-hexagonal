<?php

namespace App\Tests\Shared\Infrastructure\EventSourcing;

use App\Shared\Domain\DomainMessage;
use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Domain\EventSourcing\DuplicatePlayheadException;
use App\Shared\Domain\EventSourcing\EventStreamNotFoundException;
use App\Shared\Domain\Serializer\SimpleSerializer;
use App\Shared\Infrastructure\EventSourcing\MongoDBEventStore;
use DateTimeImmutable;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class MongoDBEventStoreTest extends TestCase
{
    protected static string $eventCollectionName = 'test_events';

    protected Client $client;

    protected MongoDBEventStore $eventStore;

    protected function setUp(): void
    {
        $this->client = new Client($_ENV['MONGODB_URL']);
        $this->client->selectDatabase($_ENV['MONGODB_DB'])->drop();
        $eventCollection = $this->client->selectCollection($_ENV['MONGODB_DB'], self::$eventCollectionName);
        $this->eventStore = new MongoDBEventStore($eventCollection, new SimpleSerializer());
        $this->eventStore->configureCollection();
    }

    public function testEventsAreAppended(): void
    {
        $domainMessages = [$this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 0)];

        $this->eventStore->append('1', $domainMessages);

        $this->assertEquals($domainMessages, $this->eventStore->load('7c36d479-771b-4c96-86ca-75fd8c41ca5e'));
    }

    public function testItThrowsAnExceptionWhenRequestingTheStreamOfANonExistingAggregate(): void
    {
        $this->expectException(EventStreamNotFoundException::class);

        $this->eventStore->load('1');
    }

    public function testItAppendsToAnAlreadyExistingStream(): void
    {
        $dateTime = new DateTimeImmutable();
        $domainEventStream = [
            $this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 0, $dateTime),
            $this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 1, $dateTime),
            $this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 2, $dateTime),
        ];
        $this->eventStore->append('7c36d479-771b-4c96-86ca-75fd8c41ca5e', $domainEventStream);
        $appendedEventStream = [
            $this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 3, $dateTime),
            $this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 4, $dateTime),
            $this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 5, $dateTime),
        ];

        $this->eventStore->append('7c36d479-771b-4c96-86ca-75fd8c41ca5e', $appendedEventStream);

        $expected = [
            $this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 0, $dateTime),
            $this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 1, $dateTime),
            $this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 2, $dateTime),
            $this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 3, $dateTime),
            $this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 4, $dateTime),
            $this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 5, $dateTime),
        ];

        $this->assertEquals($expected, $this->eventStore->load('7c36d479-771b-4c96-86ca-75fd8c41ca5e'));
    }

    public function testItThrowsAnExceptionWhenAppendingADuplicatePlayhead(): void
    {
        $eventStream = [$this->createDomainMessage('7c36d479-771b-4c96-86ca-75fd8c41ca5e', 0)];

        $this->expectException(DuplicatePlayheadException::class);

        $this->eventStore->append('7c36d479-771b-4c96-86ca-75fd8c41ca5e', $eventStream);
        $this->eventStore->append('7c36d479-771b-4c96-86ca-75fd8c41ca5e', $eventStream);
    }

    protected function createDomainMessage(string $id, int $playhead, DateTimeImmutable $dateTimeImmutable = new DateTimeImmutable()): DomainMessage
    {
        return new DomainMessage(
            $id,
            $playhead,
            new MyEvent(),
            $dateTimeImmutable
        );
    }

    public function tearDown(): void
    {
        $this->client->selectDatabase($_ENV['MONGODB_DB'])->drop();
    }
}

class MyEvent implements DomainEventInterface {
    public function serialize(): array
    {
        return [];
    }

    public static function deserialize(array $data): self
    {
        return new MyEvent();
    }
}