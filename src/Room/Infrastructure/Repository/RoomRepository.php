<?php

namespace App\Room\Infrastructure\Repository;

use App\Room\Domain\Room;
use App\Room\Domain\Repository\RoomRepositoryInterface;
use App\Shared\Domain\EventSourcing\AbstractEventSourcingRepository;

class RoomRepository extends AbstractEventSourcingRepository implements RoomRepositoryInterface
{
    public function store(Room $room): void
    {
        $this->save($room);
    }
}