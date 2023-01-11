<?php

namespace App\Shared\Infrastructure\EventSourcing;

use App\Shared\Domain\DomainMessage;
use App\Shared\Domain\EventSourcing\DuplicatePlayheadException;
use App\Shared\Domain\EventSourcing\EventStoreInterface;
use App\Shared\Domain\EventSourcing\EventStreamNotFoundException;
use App\Shared\Domain\Serializer\SerializerInterface;
use DateTimeImmutable;
use MongoDB\Collection;
use MongoDB\Driver\Exception\BulkWriteException;
use MongoDB\Model\BSONDocument;

class MongoDBEventStore implements EventStoreInterface
{
    public function __construct(
        private readonly Collection $eventCollection,
        private readonly SerializerInterface $payloadSerializer
    ) {}

    /**
     * @inheritdoc
     */
    public function load(string $id): array
    {
        $cursor = $this->eventCollection
            ->find([
                'uuid' => (string) $id,
            ], ['sort' => ['playhead' => 1]]);

        $domainMessages = [];

        foreach ($cursor as $domainMessage) {
            $domainMessages[] = $this->denormalizeDomainMessage($domainMessage);
        }

        if (empty($domainMessages)) {
            throw new EventStreamNotFoundException(sprintf('EventStream not found for aggregate with id %s', (string) $id));
        }

        return $domainMessages;
    }

    private function denormalizeDomainMessage(BSONDocument $event): DomainMessage
    {
        return new DomainMessage(
            $event['uuid'],
            $event['playhead'],
            $this->payloadSerializer->deserialize(json_decode($event['payload'], true)),
            new DateTimeImmutable($event['recorded_on'])
        );
    }

    /**
     * @inheritdoc
     */
    public function append(string $id, array $eventStream): void
    {
        $messages = [];

        foreach ($eventStream as $message) {
            $messages[] = $this->normalizeDomainMessage($message);
        }

        try {
            $this->eventCollection->insertMany($messages);
        } catch (BulkWriteException $bulkWriteException) {
            foreach ($bulkWriteException->getWriteResult()->getWriteErrors() as $writeError) {
                if (11000 === $writeError->getCode()) {
                    throw new DuplicatePlayheadException($eventStream, $bulkWriteException);
                }
            }
            throw $bulkWriteException;
        }
    }

    /**
     * @return mixed[]
     */
    private function normalizeDomainMessage(DomainMessage $message): array
    {
        return [
            'uuid' => (string) $message->getId(),
            'playhead' => $message->getPlayhead(),
            'payload' => json_encode($this->payloadSerializer->serialize($message->getPayload())),
            'recorded_on' => $message->getRecordedOn()->format('Y-m-d\TH:i:s.uP'),
            'type' => $message->getType(),
        ];
    }

    public function configureCollection(): void
    {
        $this->eventCollection->createIndex(['uuid' => 1, 'playhead' => 1], ['unique' => true]);
    }
}