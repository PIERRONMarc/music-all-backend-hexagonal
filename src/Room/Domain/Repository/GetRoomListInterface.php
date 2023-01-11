<?php

namespace App\Room\Domain\Repository;

use App\Room\Domain\ReadModel\RoomListView;

interface GetRoomListInterface
{
    /**
     * @return RoomListView[]
     */
    public function getRoomList(): array;
}