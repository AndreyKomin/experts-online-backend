<?php

namespace App\Services\Messengers\Socials;

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

    public function getToken(string $code): array
    {
        $response = $this->request('POST', $this->graphUrl . '/' . $this->version . '/oauth/access_token', [
            'json' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
                'redirect_uri' => $this->redirectUrl,
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getInfo(string $token): array
    {
        $url = $this->graphUrl.'/'.$this->version.'/me?access_token='.$token.'&fields='.implode(',', $this->fields);

        if (! empty($this->clientSecret)) {
            $appSecretProof = hash_hmac('sha256', $token, $this->clientSecret);
            $url .= '&appsecret_proof='.$appSecretProof;
        }

        $response = $this->request('GET', $url, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
