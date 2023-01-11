<?php

namespace App\Shared\Application;

interface TokenBuilderInterface
{
    /**
     * @param mixed[] $options
     * @return string
     */
    public function build(array $options = []): string;
}