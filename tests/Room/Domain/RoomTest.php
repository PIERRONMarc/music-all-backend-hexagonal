<?php

namespace App\Tests\Room\Domain;

use App\Room\Domain\Event\RoomWasCreated;
use App\Room\Domain\Room;
use App\Shared\Domain\DomainMessage;
use PHPUnit\Framework\TestCase;

class RoomTest extends TestCase
{
    public function testCreateRoomShouldAddRoomCreatedEvent(): void
    {
        $room = Room::create(
            'f38dd286-71c2-4d43-a365-5e30e2af7b2d',
            'Red Rocks',
            'Angry Duck',
            'Token'
        );

        $this->assertTrue(
            $this->assertEvent($room->getUncommittedEvents(), RoomWasCreated::class)
        );

        $this->assertSame('f38dd286-71c2-4d43-a365-5e30e2af7b2d', $room->getId());
        $this->assertSame('Red Rocks', $room->getName());
        $this->assertSame('Angry Duck', $room->getHost()->getName());
        $this->assertSame('Token', $room->getHost()->getToken());
        $this->assertEmpty($room->getSongs());
        $this->assertEmpty($room->getGuests());
    }

    /**
     * @param DomainMessage[] $uncommittedEvents
     * @param string $eventClass
     * @return bool
     */
    private function assertEvent(array $uncommittedEvents, string $eventClass): bool
    {
        foreach ($uncommittedEvents as $domainMessage) {
            if (get_class($domainMessage->getPayload()) === $eventClass) {
                return true;
            }
        }

        return false;
    }
}