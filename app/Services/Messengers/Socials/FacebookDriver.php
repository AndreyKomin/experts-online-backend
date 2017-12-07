<?php

namespace App\Services\Messengers\Socials;

use App\Services\Messengers\Dto\SocialUser;
use App\Services\Messengers\Dto\Token;
use GuzzleHttp\ClientInterface;

class FacebookDriver extends AbstractDriver
{

    protected $graphUrl = 'https://graph.facebook.com';

    protected $version = 'v2.11';

    protected $fields = ['name', 'email', 'gender', 'verified', 'link'];

    protected $scopes = ['email'];

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

    public function getToken(string $code): Token
    {
        $response = $this->request('POST', $this->graphUrl . '/' . $this->version . '/oauth/access_token', [
            'json' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
                'redirect_uri' => $this->redirectUrl,
            ]
        ]);

        $tokenResponse = json_decode($response->getBody(), true);
        return new Token([
            'token' => $tokenResponse['access_token'],
        ]);
    }

    public function getInfo(Token $token): SocialUser
    {
        $url = $this->graphUrl.'/'.$this->version.'/me?access_token='.$token->token.'&fields='.implode(',', $this->fields);

        if (! empty($this->clientSecret)) {
            $appSecretProof = hash_hmac('sha256', $token->token, $this->clientSecret);
            $url .= '&appsecret_proof='.$appSecretProof;
        }

        $response = $this->request('GET', $url, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        return new SocialUser(json_decode($response->getBody(), true));
    }
}
