<?php

namespace App\Tests\Room\Infrastructure\ReadModel;

use App\Room\Domain\Guest;
use App\Room\Domain\ReadModel\GuestView;
use App\Room\Domain\ReadModel\RoomDetailView;
use App\Room\Domain\ReadModel\RoomListView;
use App\Room\Infrastructure\ReadModel\MongoDBReadModelRoomRepository;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class MongoDBReadModelRoomRepositoryTest extends TestCase
{
    protected Client $client;

    protected MongoDBReadModelRoomRepository $repository;

    protected function setUp(): void
    {
        $this->client = new Client($_ENV['MONGODB_URL']);
        $this->client->selectDatabase($_ENV['MONGODB_DB'])->drop();
        $this->repository = new MongoDBReadModelRoomRepository($this->client);
    }

    public function testItSaveRoomDetailView(): void
    {
        $roomDetailView = new RoomDetailView(
            '1',
            'Red Rocks',
            new GuestView('Angry Duck', Guest::ROLE_ADMIN, '1'),
            [],
            []
        );

        $this->repository->saveRoomDetailView($roomDetailView);

        $bsonDocument = $this
            ->client
            ->selectDatabase($_ENV['MONGODB_DB'])
            ->selectCollection('roomDetailView')
            ->findOne(['id' => '1']);

        $savedRoomDetailView = new RoomDetailView(
            $bsonDocument['id'],
            $bsonDocument['name'],
            new GuestView($bsonDocument['host']['name'], $bsonDocument['host']['role'], $bsonDocument['host']['token']),
            [],
            []
        );

        $this->assertEquals($roomDetailView, $savedRoomDetailView);
    }

    public function testItSaveRoomListView(): void
    {
        $roomListView = new RoomListView('1', 'Red Rocks');

        $this->repository->saveRoomListView($roomListView);

        $bsonDocument = $this
            ->client
            ->selectDatabase($_ENV['MONGODB_DB'])
            ->selectCollection('roomListView')
            ->findOne(['id' => '1']);

        $savedRoomListView = new RoomListView($bsonDocument['id'], $bsonDocument['name']);

        $this->assertEquals($roomListView, $savedRoomListView);
    }

    public function testGetRoomList(): void
    {
        $roomListView = new RoomListView('1', 'Red Rocks');

        $this->repository->saveRoomListView($roomListView);

        $savedRoomListViews = $this->repository->getRoomList();

        $this->assertEquals([$roomListView], $savedRoomListViews);
    }

    public function tearDown(): void
    {
        $this->client->selectDatabase($_ENV['MONGODB_DB'])->drop();
    }
}
