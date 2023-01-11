<?php

namespace App\Tests\Shared\Domain\EventSourcing;

use App\Shared\Domain\DomainMessage;
use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Domain\EventSourcing\AbstractProjector;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ProjectorTest extends TestCase
{
    public function testEventIsProjected(): void
    {
        $domainMessage = new DomainMessage('1', 1, new MyEvent(), new DateTimeImmutable());
        $projector = new MyAbstractProjector();

        $projector->project($domainMessage);

        $this->assertTrue($projector->isHandled);
    }
}

class MyAbstractProjector extends AbstractProjector
{
    public bool $isHandled = false;

    protected function projectMyEvent(MyEvent $event): void
    {
        $this->isHandled = true;
    }
}

class MyEvent implements DomainEventInterface
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