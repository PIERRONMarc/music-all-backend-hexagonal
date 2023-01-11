<?php

namespace App\Room\Domain\ReadModel;

use App\Shared\Domain\Serializer\SerializableInterface;

class RoomListView implements SerializableInterface
{
    public function __construct(
        private string $id,
        private string $name
    ) {}

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

    public static function deserialize(array $data): SerializableInterface
    {
        return new self($data['id'], $data['name']);
    }

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
}