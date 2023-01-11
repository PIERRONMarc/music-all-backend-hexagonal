<?php

namespace App\Room\Application\Query\GetRoomList;

use App\Room\Domain\ReadModel\RoomListView;
use App\Room\Domain\Repository\GetRoomListInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

class GetRoomListQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly GetRoomListInterface $repository
    ) {}

    /**
     * @return RoomListView[]
     */
    public function __invoke(GetRoomListQuery $getRoomListQuery): array
    {
        return $this->repository->getRoomList();
    }
}