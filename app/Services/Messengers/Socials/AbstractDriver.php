<?php

namespace App\Services\Messengers\Socials;

use App\Contracts\ISocialDriver;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractDriver implements ISocialDriver
{
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    protected function request(string $method, string $url, array $options): ResponseInterface
    {
        return $this->client->request($method, $url, $options);
    }
}
