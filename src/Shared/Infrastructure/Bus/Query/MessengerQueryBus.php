<?php

namespace App\Shared\Infrastructure\Bus\Query;

use App\Shared\Domain\Bus\Query\QueryBusInterface;
use App\Shared\Domain\Bus\Query\QueryInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class MessengerQueryBus implements QueryBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $symfonyMessageBus
    ) {}

    /**
     * @throws HandlerFailedException
     */
    public function dispatch(QueryInterface $query): mixed
    {
        $envelope = $this->symfonyMessageBus->dispatch($query);

        /** @var HandledStamp $stamp */
        $stamp = $envelope->last(HandledStamp::class);

        return $stamp->getResult();
    }
}