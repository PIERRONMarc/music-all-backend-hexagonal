<?php

namespace App\Shared\Domain\EventSourcing;

use RuntimeException;

/**
 * Exceptions thrown by event store implementations.
 */
abstract class EventStoreException extends RuntimeException
{
}