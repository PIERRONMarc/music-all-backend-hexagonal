<?php

namespace App\Shared\Domain;

use App\Shared\Domain\Event\DomainEventInterface;
use DateTimeImmutable;

final class DomainMessage
{
    public function __construct(
        private readonly string $id,
        private readonly int $playhead,
        private readonly DomainEventInterface $payload,
        private readonly DateTimeImmutable $recordedOn
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getPlayhead(): int
    {
        return $this->playhead;
    }

    public function getPayload(): DomainEventInterface
    {
        return $this->payload;
    }

    public function getRecordedOn(): DateTimeImmutable
    {
        return $this->recordedOn;
    }

    public function getType(): string
    {
        return strtr(get_class($this->payload), '\\', '.');
    }
}