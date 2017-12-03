<?php

namespace App\Services\Messengers\Socials;


use GuzzleHttp\ClientInterface;

class GoogleProvider extends AbstractDriver
{
    protected $clientSecret;

    protected $clientId;

    protected $redirectUrl;

    public function __construct(ClientInterface $client, array $config)
    {
        parent::__construct($client);
        $this->clientSecret = $config['clientSecret'];
        $this->clientId = $config['clientId'];
        $this->redirectUrl = $config['redirectUrl'];
    }

    public function getToken(string $code): array
    {

    }

    public function getInfo(string $token): array
    {

    }

}
