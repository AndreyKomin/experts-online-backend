<?php

namespace App\Services;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class TelegramService
{
    protected $client;

    protected $uri = 'temp';

    protected static $defaultHeaders = [
        'Accept' => 'application/json',
        'Content-type' => 'application/json',
    ];

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function sendAuth(string $uniqueId): void
    {
        $this->request('POST', 'sendmessage', [
            'json' => ['chatId' => $uniqueId],
            'headers' => static::$defaultHeaders,
        ]);
    }

    protected function request(string $method, string $endpoint, array $options = []): ResponseInterface
    {
        return $this->client->request('POST', "{$this->uri}{$endpoint}", $options);
    }
}