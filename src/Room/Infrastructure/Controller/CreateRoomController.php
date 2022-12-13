<?php

namespace App\Room\Infrastructure\Controller;

use App\Room\Application\Command\CreateRoom\CreateRoomCommand;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/room', name: 'create_room', methods: ['POST'])]
class CreateRoomController extends AbstractController
{

    public function __invoke(CommandBusInterface $commandBus): Response
    {
        $id = Uuid::v4();
        $command = new CreateRoomCommand($id);
        $commandBus->dispatch($command);

        return $this->json(['id' => $id], Response::HTTP_CREATED);
    }
}