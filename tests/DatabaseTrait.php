<?php

namespace App\Tests;

trait DatabaseTrait
{
    protected function clearMongoDB(): void
    {
        $client = static::getContainer()->get('app.mongodb_client');
        $db = $client->selectDatabase($_ENV['MONGODB_DB']);

        foreach ($db->listCollections() as $collection) {
            $db->dropCollection($collection->getName());
        }
    }
}