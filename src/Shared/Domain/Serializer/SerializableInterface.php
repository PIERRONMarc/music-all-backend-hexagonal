<?php

namespace App\Shared\Domain\Serializer;

interface SerializableInterface
{
    /**
     * @return mixed[]
     */
    public function serialize(): array;

    /**
     * @param mixed[] $data
     */
    public static function deserialize(array $data): self;
}