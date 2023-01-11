<?php

namespace App\Room\Application\Command\CreateRoom;

use App\Shared\Domain\Bus\Command\CommandInterface;

class CreateRoomCommand implements CommandInterface
{
    public function __construct(
        private string $id
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }
}