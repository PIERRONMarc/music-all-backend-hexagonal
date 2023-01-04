<?php

namespace App\Room\Domain;

interface RoomRepositoryInterface
{
    public function store(Room $room): void;
}