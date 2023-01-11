<?php

namespace App\Tests\Shared\Infrastructure;

use App\Shared\Infrastructure\JWTBuilder;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use PHPUnit\Framework\TestCase;

class JWTBuilderTest extends TestCase
{

    public const PRIVATE_KEY = __DIR__.'/../../../Config/jwt/private.pem';

    public function testTokenGeneration(): void
    {
        $tokenBuilder = new JWTBuilder(self::PRIVATE_KEY);
        $token = $tokenBuilder->build();

        $this->assertSame($token, (new Parser(new JoseEncoder()))->parse($token)->toString());
    }

    public function testClaims(): void
    {
        $tokenBuilder = new JWTBuilder(self::PRIVATE_KEY);
        $token = $tokenBuilder->build([
            'claims' => ['roomId' => '7bddd678-9053-43b1-9620-747c52faa479'],
        ]);

        /** @var Plain $token **/
        $token = (new Parser(new JoseEncoder()))->parse($token);

        $this->assertSame('7bddd678-9053-43b1-9620-747c52faa479', $token->claims()->get('roomId'));
    }
}