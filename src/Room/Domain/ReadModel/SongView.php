<?php

namespace App\Room\Domain\ReadModel;

use App\Shared\Domain\Serializer\SerializableInterface;

class SongView implements SerializableInterface
{
    public function __construct(
        private string $id,
        private string $url,
        private bool $isPaused = false
    ) {}

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'isPaused' => $this->isPaused
        ];
    }

    public static function deserialize(array $data): SerializableInterface
    {
        return new self($data['id'], $data['url'], $data['isPaused']);
    }
}