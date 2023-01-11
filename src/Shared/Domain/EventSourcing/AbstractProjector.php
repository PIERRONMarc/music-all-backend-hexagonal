<?php

namespace App\Shared\Domain\EventSourcing;

use App\Shared\Domain\DomainMessage;
abstract class AbstractProjector
{
    public function project(DomainMessage $domainMessage): void
    {
        $event = $domainMessage->getPayload();
        $method = $this->getProjectMethod($event);

        if (!method_exists($this, $method)) {
            return;
        }

        $this->$method($event, $domainMessage);
    }

    private function getProjectMethod(object $event): string
    {
        $classParts = explode('\\', get_class($event));

        return 'project'.end($classParts);
    }
}