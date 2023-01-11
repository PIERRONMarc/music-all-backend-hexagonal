<?php

namespace App\Room\Domain\ReadModel;

use App\Shared\Domain\Serializer\SerializableInterface;

class RoomDetailView implements SerializableInterface
{
    /**
     * @param SongView[] $songs
     * @param GuestView[] $guests
     */
    public function __construct(
        private string $id,
        private string $name,
        private GuestView $host,
        private array $songs,
        private array $guests,
    ) {}

    public function serialize(): array
    {
        $guests = $songs = [];
        foreach ($this->guests as $guest) {
            $guests[] = $guest->serialize();
        }
        foreach ($this->songs as $song) {
            $songs[] = $song->serialize();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'host' => $this->host->serialize(),
            'songs' => $songs,
            'guests' => $guests
        ];
    }

    public static function deserialize(array $data): self
    {
        $guests = $songs = [];
        foreach ($data['guests'] as $guest) {
            $guests[] = new GuestView($guest['name'], $guest['role'], $guest['token']);
        }
        foreach ($data['songs'] as $song) {
            $songs[] = new SongView($song['id'], $song['url'], $song['isPaused']);
        }

        return new self(
            $data['id'],
            $data['name'],
            new GuestView($data['host']['name'], $data['host']['role'], $data['host']['token']),
            $songs,
            $guests
        );
    }
}