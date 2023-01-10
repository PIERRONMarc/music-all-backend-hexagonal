<?php

namespace App\Tests\Room\Infrastructure\ReadModel;

use App\Room\Domain\Event\RoomWasCreated;
use App\Room\Domain\Guest;
use App\Room\Infrastructure\ReadModel\MongoDBReadModelRoomRepository;
use App\Room\Infrastructure\ReadModel\RoomProjection;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class RoomProjectionTest extends TestCase
{
    private Client $client;

    private RoomProjection $roomProjection;

    protected function setUp(): void
    {
        $this->client = new Client($_ENV['MONGODB_URL']);
        $repository = new MongoDBReadModelRoomRepository($this->client);
        $this->roomProjection = new RoomProjection($repository);
    }

    public function testEventSubscription(): void
    {
        $this->assertArrayHasKey(RoomWasCreated::class, RoomProjection::getSubscribedEvents());
    }

    public function testRoomWasCreatedProjection(): void
    {
        $roomWasCreated = new RoomWasCreated('1', 'Red Rocks', 'Angry Duck', '1');

        $this->roomProjection->projectWhenRoomWasCreated($roomWasCreated);

        $bsonDocument = $this->client->selectDatabase($_ENV['MONGODB_DB'])->selectCollection('roomListView')->findOne(['id' => '1']);
        $this->assertSame($bsonDocument['id'], '1');
        $this->assertSame($bsonDocument['name'], 'Red Rocks');

        $bsonDocument = $this->client->selectDatabase($_ENV['MONGODB_DB'])->selectCollection('roomDetailView')->findOne(['id' => '1']);
        $this->assertSame($bsonDocument['id'], '1');
        $this->assertSame($bsonDocument['name'], 'Red Rocks');
        $this->assertSame($bsonDocument['host']['name'], 'Angry Duck');
        $this->assertSame($bsonDocument['host']['role'], Guest::ROLE_ADMIN);
        $this->assertSame($bsonDocument['host']['token'], '1');
    }

    public function tearDown(): void
    {
        $this->client->selectDatabase($_ENV['MONGODB_DB'])->drop();
    }
}
