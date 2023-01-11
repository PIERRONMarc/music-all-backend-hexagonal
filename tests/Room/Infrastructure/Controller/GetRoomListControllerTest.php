<?php

namespace App\Tests\Room\Infrastructure\Controller;

use App\Tests\DatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class GetRoomListControllerTest extends WebTestCase
{
    use DatabaseTrait;

    protected ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testGetRoomList(): void
    {
        $this->client->jsonRequest(Request::METHOD_POST, '/room');
        $roomId = json_decode($this->client->getResponse()->getContent(), true)['id'];

        $this->client->jsonRequest(Request::METHOD_GET, '/room');
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals($roomId, $data[0]['id']);
    }

    protected function tearDown(): void
    {
        $this->client = null;
        $this->clearMongoDB();
        parent::tearDown();
    }
}