<?php

namespace App\Room\Domain;

class Room
{
    private string $id;

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

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

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
}