<?php

namespace App\Room\Domain;

interface RoomRepositoryInterface
{
    public function save(Room $room): void;
}