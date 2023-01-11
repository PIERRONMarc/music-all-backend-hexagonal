<?php

namespace App\Room\Domain\Repository;

use App\Room\Domain\Room;

interface RoomRepositoryInterface
{
    public function store(Room $room): void;
}