<?php

namespace App\Shared\Domain\Serializer;

use RuntimeException;

/**
* Exception thrown if an error occurs during (de)serialization.
*/
class SerializationException extends RuntimeException
{
}
