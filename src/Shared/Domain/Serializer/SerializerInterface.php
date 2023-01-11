<?php

namespace App\Shared\Domain\Serializer;

interface SerializerInterface
{
    /**
     * @return mixed[]
     */
    public function serialize(object $object): array;

    /**
     * @param mixed[] $serializedObject
     */
    public function deserialize(array $serializedObject): mixed;
}