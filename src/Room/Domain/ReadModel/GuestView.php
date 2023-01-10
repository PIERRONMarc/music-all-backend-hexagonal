<?php

namespace App\Room\Domain\ReadModel;

use App\Shared\Domain\Serializer\SerializableInterface;

class GuestView implements SerializableInterface
{
    public function __construct(
        private string $name,
        private string $role,
        private string $token
    ) {}

    public function serialize(): array
    {
        return [
            'name' => $this->name,
            'role' => $this->role,
            'token' => $this->token
        ];
    }

    public static function deserialize(array $data): SerializableInterface
    {
        return new self($data['name'], $data['role'], $data['token']);
    }
}