<?php

namespace App\Room\Infrastructure\Repository;

use App\Room\Domain\Room;
use App\Room\Domain\RoomRepositoryInterface;

class RoomRepository implements RoomRepositoryInterface
{
    public function save(Room $room): void
    {
        // TODO save into somethings
    }
}