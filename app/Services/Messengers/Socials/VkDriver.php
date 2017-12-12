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
        $url = $this->tokenUrl . '?' . 'client_id=' . $this->clientId . '&client_secret='
            . $this->clientSecret . '&redirect_uri=' . $this->redirectUrl . '&code='. $code;

        $response = $this->request('GET',$url, []);

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
        $data = json_decode($response->getBody(), true);

        return new SocialUser([
            'id' => (string)$data['response'][0]['uid'],
            'name' => $data['response'][0]['first_name'] . ' ' . $data['response'][0]['last_name'],
        ]);
    }
}
