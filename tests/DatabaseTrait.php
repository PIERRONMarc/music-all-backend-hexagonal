<?php

namespace App\Tests;

trait DatabaseTrait
{
    protected function clearEventStore(): void
    {
        $client = static::getContainer()->get('app.event_store.mongodb_client');
        $db = $client->selectDatabase($_ENV['MONGODB_DB']);

        foreach ($db->listCollections() as $collection) {
            $db->dropCollection($collection->getName());
        }
    }
}