<?php

namespace App\Config;

use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

final class JwtIssuer
{

    private Configuration $config;

    public function __construct()
    {
        $this->config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText('testing'));
    }

    public function getConfig(): Configuration
    {
        return $this->config;
    }

    /**
     * @throws \Exception
     */
    public function issueToken(): \Lcobucci\JWT\Token\Plain
    {
        $now = new DateTimeImmutable();
        return $this->config->builder()
            ->issuedBy('http://pet-shop.com')
            ->withHeader('iss', 'http://pet-shop.com')
            ->permittedFor('http://pet-shop.org')
            ->identifiedBy('4f1g23a12aa')
            ->relatedTo('user123')
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('uid', 1)
            ->getToken($this->config->signer(), $this->config->signingKey());
    }
}
