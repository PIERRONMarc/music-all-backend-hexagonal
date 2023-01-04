<?php

namespace App\Room\Domain;

use App\Room\Domain\Event\RoomWasCreated;
use App\Shared\Domain\Aggregate\AbstractEventSourcedAggregateRoot;

class Room extends AbstractEventSourcedAggregateRoot
{
    private string $name;

    private Guest $host;

    /**
     * @var Song[]
     */
    private array $songs = [];

    /**
     * @var Guest[]
     */
    private array $guests = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getHost(): Guest
    {
        return $this->host;
    }

    public function setHost(Guest $host): self
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return Song[]
     */
    public function getSongs(): array
    {
        return $this->songs;
    }

    public function addSong(Song $song): self
    {
        $this->songs[] = $song;
        return $this;
    }

    /**
     * @return Guest[]
     */
    public function getGuests(): array
    {
        return $this->guests;
    }

    public function addGuest(Guest $guest): self
    {
        $this->guests[] = $guest;
        return $this;
    }

    public static function create(
        string $roomId,
        string $roomName,
        string $hostName,
        string $hostToken
    ): self
    {
        $room = new Room();
        $room->apply(new RoomWasCreated($roomId, $roomName, $hostName, $hostToken));

        return $room;
    }

    protected function applyRoomWasCreated(RoomWasCreated $event): void
    {
        $host = (new Guest())
            ->setName($event->hostName)
            ->setRole(Guest::ROLE_ADMIN)
            ->setToken($event->hostToken)
        ;

        $this
            ->setHost($host)
            ->setName($event->roomName)
            ->setId($event->roomId)
        ;
    }
}
