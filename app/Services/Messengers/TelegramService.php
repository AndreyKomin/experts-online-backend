<?php

namespace App\Messengers\Services;

use App\Contracts\IMessengerService;
use App\Models\User;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class TelegramService implements IMessengerService
{
    protected $client;

    protected $uri = 'https://ekbrand.tk/express/';

    protected static $defaultHeaders = [
        'Accept' => 'application/json',
        'Content-type' => 'application/json',
    ];

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function sendAuth(User $user, array $options = []): void
    {
        $this->request('POST', 'auth', [
            'json' => ['chatId' => $options['code']],
            'headers' => static::$defaultHeaders,
        ]);
    }

    protected function request(string $method, string $endpoint, array $options = []): ResponseInterface
    {
        return $this->client->request('POST', "{$this->uri}{$endpoint}", $options);
    }
}