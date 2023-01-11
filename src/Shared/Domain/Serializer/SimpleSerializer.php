<?php

namespace App\Shared\Domain\Serializer;

class SimpleSerializer implements SerializerInterface
{
    /**
     * @inheritdoc
     */
    public function serialize(object $object): array
    {
        if (!$object instanceof SerializableInterface) {
            throw new SerializationException(sprintf('Object \'%s\' does not implement App\Shared\Domain\Serializer\Serializable', get_class($object)));
        }

        return [
            'class' => get_class($object),
            'payload' => $object->serialize(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function deserialize(array $serializedObject): mixed
    {
        if (!array_key_exists('class', $serializedObject)) {
            throw new SerializationException("Key 'class' should be set.");
        }

        if (!array_key_exists('payload', $serializedObject)) {
            throw new SerializationException("Key 'payload' should be set.");
        }

        if (!in_array(SerializableInterface::class, class_implements($serializedObject['class']))) {
            throw new SerializationException(sprintf('Class \'%s\' does not implement App\Shared\Domain\Serializer\Serializable', $serializedObject['class']));
        }

        return $serializedObject['class']::deserialize($serializedObject['payload']);
    }
}