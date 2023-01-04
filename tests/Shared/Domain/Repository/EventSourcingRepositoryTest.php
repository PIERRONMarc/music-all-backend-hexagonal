<?php

namespace App\Tests\Shared\Domain\Repository;

use App\Room\Domain\Room;
use App\Shared\Domain\Bus\Event\EventBusInterface;
use App\Shared\Domain\Bus\Event\EventListenerInterface;
use App\Shared\Domain\EventSourcing\AbstractEventSourcingRepository;
use App\Shared\Domain\EventSourcing\EventStoreInterface;
use PHPUnit\Framework\TestCase;

class EventSourcingRepositoryTest extends TestCase
{
    public function testEventsAreLoadedAndPublished(): void
    {
        $eventStore = new EventStoreStub();
        $eventBus = new EventBusStub();
        $repository = new MyAbstractEventSourcedRepository($eventStore, $eventBus);
        $room = new Room();
        $room->setId('1');
        $repository->save($room);

        $this->assertTrue($eventStore->hasAppend);
        $this->assertTrue($eventBus->hasPublished);
    }
}

class EventBusStub implements EventBusInterface
{
    public bool $hasPublished = false;

    public function subscribe(EventListenerInterface $eventListener): void {}

    /**
     * @inheritdoc
     */
    public function publish(array $domainMessages): void
    {
        $this->hasPublished = true;
    }
}

class EventStoreStub implements EventStoreInterface
{
    public bool $hasAppend = false;

    public function load(string $id): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function append(string $id, array $eventStream): void
    {
        $this->hasAppend = true;
    }
}

class MyAbstractEventSourcedRepository extends AbstractEventSourcingRepository {}
