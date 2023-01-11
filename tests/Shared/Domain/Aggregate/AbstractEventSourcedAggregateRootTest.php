<?php

namespace App\Tests\Shared\Domain\Aggregate;

use App\Shared\Domain\Aggregate\AbstractEventSourcedAggregateRoot;
use App\Shared\Domain\Event\DomainEventInterface;
use PHPUnit\Framework\TestCase;

class AbstractEventSourcedAggregateRootTest extends TestCase
{
    public function testPlayheadIncrementWhenApply(): void
    {
        $aggregateRoot = new MyTestEventSourcedAggregateRoot();
        $aggregateRoot->setId('1');
        $aggregateRoot->apply(new AggregateEvent());
        $aggregateRoot->apply(new AggregateEvent());
        $eventStream = $aggregateRoot->getUncommittedEvents();

        $i = 0;
        foreach ($eventStream as $domainMessage) {
            $this->assertEquals($i, $domainMessage->getPlayhead());
            ++$i;
        }
        $this->assertEquals(2, $i);
    }

    public function testApplyIsCalledForEvent(): void
    {
        $aggregateRoot = new MyTestEventSourcedAggregateRoot();
        $aggregateRoot->setId('1');

        $aggregateRoot->apply(new AggregateEvent());

        $this->assertTrue($aggregateRoot->isCalled);
    }

    public function testPlayheadIsSetWhenInitializing(): void
    {
        $aggregateRoot = new MyTestEventSourcedAggregateRoot();
        $aggregateRoot->setId('1');
        $aggregateRoot->initializeState([new AggregateEvent()]);

        $aggregateRoot->apply(new AggregateEvent());

        $eventStream = $aggregateRoot->getUncommittedEvents();
        foreach ($eventStream as $domainMessage) {
            $this->assertEquals(1, $domainMessage->getPlayhead());
        }
    }

}

class MyTestEventSourcedAggregateRoot extends AbstractEventSourcedAggregateRoot
{
    public bool $isCalled = false;

    public function applyAggregateEvent(): void
    {
        $this->isCalled = true;
    }


}

class AggregateEvent implements DomainEventInterface
{
    public function serialize(): array
    {
        return [];
    }

    public static function deserialize(array $data): self
    {
        return new self();
    }
}
