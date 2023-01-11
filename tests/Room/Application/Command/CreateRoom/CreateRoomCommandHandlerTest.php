<?php

namespace App\Tests\Room\Application\Command\CreateRoom;

use App\Room\Application\Command\CreateRoom\CreateRoomCommand;
use App\Room\Application\Command\CreateRoom\CreateRoomCommandHandler;
use App\Room\Domain\Room;
use App\Room\Domain\Repository\RoomRepositoryInterface;
use App\Shared\Application\TokenBuilderInterface;
use App\Shared\Domain\RandomGuestNameGeneratorInterface;
use App\Shared\Domain\RandomRoomNameGeneratorInterface;
use PHPUnit\Framework\TestCase;

class CreateRoomCommandHandlerTest extends TestCase
{
    public function testItCreatesRedRocksRoomWhenInvoked(): void
    {
        $repository = $this->createMock(RoomRepositoryInterface::class);
        $repository->expects(self::once())
            ->method('store')
            ->with(self::callback(
                fn(Room $room): bool => $room->getName() === 'Red Rocks'
                    && $room->getHost()->getName() === 'Angry Duck'
                    && $room->getHost()->getToken() === 'Token'
                    && $room->getHost()->getRole() === 'ADMIN'
                    && $room->getSongs() === []
                    && $room->getId() === 'd8092592-74b4-47e8-863d-df7061a6293d'
            ))
        ;

        $randomRoomNameGenerator = $this->createMock(RandomRoomNameGeneratorInterface::class);
        $randomRoomNameGenerator->method('generate')->willReturn('Red Rocks');
        $randomGuestNameGenerator = $this->createMock(RandomGuestNameGeneratorInterface::class);
        $randomGuestNameGenerator->method('generate')->willReturn('Angry Duck');
        $tokenFactory = $this->createMock(TokenBuilderInterface::class);
        $tokenFactory->method('build')->willReturn('Token');
        $handler = new CreateRoomCommandHandler(
            $repository,
            $randomRoomNameGenerator,
            $randomGuestNameGenerator,
            $tokenFactory
        );

        $command = new CreateRoomCommand('d8092592-74b4-47e8-863d-df7061a6293d');
        $handler($command);
    }

    public function testItRandomlyCreatesRoomWhenInvoked(): void
    {
        $repository = $this->createMock(RoomRepositoryInterface::class);
        $repository->expects(self::once())
            ->method('store')
            ->with(self::callback(
                fn(Room $room): bool => $room->getName() === 'O2 Arena'
                    && $room->getHost()->getName() === 'Adorable Duck'
                    && $room->getHost()->getToken() === 'AnotherToken'
                    && $room->getHost()->getRole() === 'ADMIN'
                    && $room->getSongs() === []
                    && $room->getId() === 'd8092592-74b4-47e8-863d-df7061a6293d'
            ))
        ;

        $randomRoomNameGenerator = $this->createMock(RandomRoomNameGeneratorInterface::class);
        $randomRoomNameGenerator->method('generate')->willReturn('O2 Arena');
        $randomGuestNameGenerator = $this->createMock(RandomGuestNameGeneratorInterface::class);
        $randomGuestNameGenerator->method('generate')->willReturn('Adorable Duck');
        $tokenFactory = $this->createMock(TokenBuilderInterface::class);
        $tokenFactory->method('build')->willReturn('AnotherToken');
        $handler = new CreateRoomCommandHandler(
            $repository,
            $randomRoomNameGenerator,
            $randomGuestNameGenerator,
            $tokenFactory
        );

        $command = new CreateRoomCommand('d8092592-74b4-47e8-863d-df7061a6293d');
        $handler($command);
    }
}