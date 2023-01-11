<?php

namespace App\Room\Domain\Event;

use App\Shared\Domain\Event\DomainEventInterface;

class RoomWasCreated implements DomainEventInterface
{
    public function __construct(
        public string $roomId,
        public string $roomName,
        public string $hostName,
        public string $hostToken
    ) {}

    public function serialize(): array
    {
        return [
            'roomId' => $this->roomId,
            'roomName' => $this->roomName,
            'hostName' => $this->hostName,
            'hostToken' => $this->hostToken
        ];
    }

    public static function deserialize(array $data): self
    {
        return new self(
            $data['roomId'],
            $data['roomName'],
            $data['hostName'],
            $data['hostToken']
        );
    }
}