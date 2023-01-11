<?php

namespace App\Room\Application\Command\CreateRoom;

use App\Room\Domain\Room;
use App\Room\Domain\Repository\RoomRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\TokenBuilderInterface;
use App\Shared\Domain\RandomGuestNameGeneratorInterface;
use App\Shared\Domain\RandomRoomNameGeneratorInterface;

class CreateRoomCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly RoomRepositoryInterface $repository,
        private readonly RandomRoomNameGeneratorInterface $randomRoomNameGenerator,
        private readonly RandomGuestNameGeneratorInterface $randomGuestNameGenerator,
        private readonly TokenBuilderInterface $tokenBuilder
    ) {}

    public function __invoke(CreateRoomCommand $command): void
    {
        $hostName = $this->randomGuestNameGenerator->generate();
        $hostToken = $this->tokenBuilder->build([
            'claims' => [
                'guestName' => $hostName,
                'roomId' => $command->getId(),
            ],
        ]);

        $room = Room::create(
            $command->getId(),
            $this->randomRoomNameGenerator->generate(),
            $hostName,
            $hostToken
        );

        $this->repository->store($room);
    }
}