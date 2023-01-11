<?php

namespace App\Shared\Domain\EventSourcing;

/**
* Exception thrown if an event stream is not found.
*/
final class EventStreamNotFoundException extends EventStoreException
{
}