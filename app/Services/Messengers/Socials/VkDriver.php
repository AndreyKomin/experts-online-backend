<?php

namespace App\Services\Messengers\Socials;

use App\Services\Messengers\Dto\SocialUser;
use App\Services\Messengers\Dto\Token;
use GuzzleHttp\ClientInterface;

class VkDriver extends AbstractDriver
{
    protected $clientSecret;

    protected $clientId;

    protected $redirectUrl;

    protected $tokenUrl = 'https://oauth.vk.com/access_token';

    protected $meUrl = 'https://api.vk.com/method/users.get';

    public function __construct(ClientInterface $client, array $config)
    {
        parent::__construct($client);
        $this->clientSecret = $config['clientSecret'];
        $this->clientId = $config['clientId'];
        $this->redirectUrl = $config['redirectUrl'];
    }

    public function getToken(string $code): Token
    {

        $response = $this->request('POST', $this->tokenUrl, [
            'json' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
                'redirect_uri' => $this->redirectUrl,
            ],
            'headers' => ['Accept' => 'application/json']
        ]);

        $tokenResponse = json_decode($response->getBody(), true);
        return new Token([
            'token' => $tokenResponse['access_token'],
        ]);
    }

    public function getInfo(Token $token): SocialUser
    {

        $response = $this->request('GET', $this->meUrl, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '. $token->token,
            ],
            'query' => [
                'access_token' => $token->token,
            ]
        ]);

        return new SocialUser(json_decode($response->getBody(), true));
    }
}
