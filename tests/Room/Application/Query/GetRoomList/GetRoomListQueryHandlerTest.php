<?php

namespace App\Tests\Room\Application\Query\GetRoomList;

use App\Room\Application\Query\GetRoomList\GetRoomListQuery;
use App\Room\Application\Query\GetRoomList\GetRoomListQueryHandler;
use App\Room\Domain\ReadModel\RoomListView;
use App\Room\Domain\Repository\GetRoomListInterface;
use PHPUnit\Framework\TestCase;

class GetRoomListQueryHandlerTest extends TestCase
{
    public function testItGetsAllRoom(): void
    {
        $expected = [new RoomListView('1', 'Red rocks')];
        $repository = $this->createMock(GetRoomListInterface::class);
        $repository->expects($this->once())
            ->method('getRoomList')
            ->willReturn($expected)
        ;
        $handler = new GetRoomListQueryHandler($repository);
        $command = new GetRoomListQuery();

        $roomListViews = $handler($command);

        $this->assertEquals($expected, $roomListViews);
    }
}