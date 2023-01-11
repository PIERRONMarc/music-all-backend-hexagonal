<?php

namespace App\Shared\Domain\Bus\Query;

interface QueryBusInterface
{
    public function dispatch(QueryInterface $query): mixed;
}