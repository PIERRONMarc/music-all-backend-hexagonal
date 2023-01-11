<?php

namespace App\Room\Infrastructure\Controller;

use App\Room\Application\Query\GetRoomList\GetRoomListQuery;
use App\Shared\Domain\Bus\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/room', name: 'get_room_list', methods: ['GET'])]
class GetRoomListController extends AbstractController
{
    public function __invoke(QueryBusInterface $queryBus): Response
    {
        $query = new GetRoomListQuery();
        $roomListViews = $queryBus->dispatch($query);

        return $this->json($roomListViews);
    }
}