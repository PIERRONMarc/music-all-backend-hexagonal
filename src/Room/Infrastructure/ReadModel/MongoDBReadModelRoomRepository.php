<?php

namespace App\Room\Infrastructure\ReadModel;

use App\Room\Domain\ReadModel\RoomDetailView;
use App\Room\Domain\ReadModel\RoomListView;
use App\Room\Domain\Repository\GetRoomListInterface;
use MongoDB\Client;

class MongoDBReadModelRoomRepository implements GetRoomListInterface
{
    public function __construct(
        private readonly Client $client
    ) {}

    public function saveRoomDetailView(RoomDetailView $roomDetailView): void
    {
        $this
            ->client
            ->selectDatabase($_ENV['MONGODB_DB'])
            ->selectCollection('roomDetailView')
            ->insertOne($roomDetailView->serialize());
    }

    public function saveRoomListView(RoomListView $roomListView): void
    {
        $this
            ->client
            ->selectDatabase($_ENV['MONGODB_DB'])
            ->selectCollection('roomListView')
            ->insertOne($roomListView->serialize());
    }

    /**
     * @inheritdoc
     */
    public function getRoomList(): array
    {
        $bsonDocuments = $this
            ->client
            ->selectDatabase($_ENV['MONGODB_DB'])
            ->selectCollection('roomListView')
            ->find();

        $roomListViews = [];
        foreach ($bsonDocuments as $roomListView) {
            $roomListViews[] = new RoomListView($roomListView['id'], $roomListView['name']);
        }

        return $roomListViews;
    }
}