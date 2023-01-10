<?php

namespace App\Room\Infrastructure\ReadModel;

use App\Room\Domain\Event\RoomWasCreated;
use App\Room\Domain\Guest;
use App\Room\Domain\ReadModel\GuestView;
use App\Room\Domain\ReadModel\RoomListView;
use App\Room\Domain\ReadModel\RoomProjectionInterface;
use App\Room\Domain\ReadModel\RoomDetailView;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RoomProjection implements RoomProjectionInterface, EventSubscriberInterface
{
    public function __construct(
        private readonly MongoDBReadModelRoomRepository $repository
    ) {}

    public function projectWhenRoomWasCreated(RoomWasCreated $roomWasCreated): void
    {
        $roomDetailView = new RoomDetailView(
            $roomWasCreated->roomId,
            $roomWasCreated->roomName,
            new GuestView($roomWasCreated->hostName, Guest::ROLE_ADMIN, $roomWasCreated->hostToken),
            [],
            []
        );
        $this->repository->saveRoomDetaiLView($roomDetailView);

        $roomListView = new RoomListView($roomWasCreated->roomId, $roomWasCreated->roomName);
        $this->repository->saveRoomListView($roomListView);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RoomWasCreated::class => 'projectWhenRoomWasCreated'
        ];
    }
}