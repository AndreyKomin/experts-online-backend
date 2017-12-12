<?php

namespace App\Services\Messengers\Socials;


use App\Services\Messengers\Dto\SocialUser;
use App\Services\Messengers\Dto\Token;
use GuzzleHttp\ClientInterface;

class GoogleDriver extends AbstractDriver
{
    protected $clientSecret;

    protected $clientId;

    protected $redirectUrl;

    protected $tokenUrl = 'https://accounts.google.com/o/oauth2/token';

    protected $meUrl = 'https://www.googleapis.com/plus/v1/people/me';

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

        $response = $this->request('POST',$url, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $tokenResponse = json_decode($response->getBody(), true);
        return new Token([
            'token' => $tokenResponse['access_token'],
        ]);
    }

    public function getInfo(Token $token): SocialUser
    {

        $response = $this->request('GET', $this->meUrl, [
            'query' => [
                'prettyPrint' => 'false',
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token->token,
            ],
        ]);

        return new SocialUser(json_decode($response->getBody(), true));
    }
}
