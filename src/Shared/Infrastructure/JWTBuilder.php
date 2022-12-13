<?php

namespace App\Shared\Infrastructure;

use App\Shared\Application\TokenBuilderInterface;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class JWTBuilder implements TokenBuilderInterface
{

    private Configuration $config;

    public function __construct(string $jwtSecretKeyPath)
    {
        $this->config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::file($jwtSecretKeyPath)
        );
    }
    public function build(array $options = []): string
    {
        $builder = $this->config->builder();
        $claims = $options['claims'] ?? [];

        foreach ($claims as $name => $value) {
            $builder->withClaim($name, $value);
        }

        return $builder->getToken($this->config->signer(), $this->config->signingKey())->toString();
    }
}