<?php

namespace App\Tests\Room\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateRoomControllerTest extends WebTestCase
{
    protected ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testRoomCreation(): void
    {
        $this->client->request(Request::METHOD_POST, '/room');
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $uuidPattern = "/^\{?[0-9a-f]{8}-?[0-9a-f]{4}-?[0-9a-f]{4}-?[0-9a-f]{4}-?[0-9a-f]{12}}?$/i";
        $this->assertMatchesRegularExpression($uuidPattern, $data['id'] ?? false, 'Invalid UUID');

        $this->assertResponseIsSuccessful();
    }
}