<?php

namespace App\Room\Domain\ReadModel;

use App\Room\Domain\Event\RoomWasCreated;

interface RoomProjectionInterface
{
    public function projectWhenRoomWasCreated(RoomWasCreated $roomWasCreated): void;
}