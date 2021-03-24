<?php

namespace Services\Nibe;

use League\OAuth2\Client\Token\AccessToken;
use Olssonm\OAuth2\Client\Provider\Nibe;

use Storage;

class Client
{
    protected $token;

    protected $client;

    public function __construct()
    {
        // If token is present
        if (Storage::disk('token')->has('token.txt')) {
            $data = Storage::disk('token')->get('token.txt');
            $this->token = new AccessToken(json_decode($data, true));
        }

        $this->client = new Nibe(config('services.nibe') + [
            'redirectUri' => route('authorize.capture'),
            'scope' => ['READSYSTEM']
        ]);
    }

    public function getAuthorizationUrl(): string
    {
        return $this->client->getAuthorizationUrl();
    }

    public function setAccessToken(string $code)
    {
        $this->token = $this->client->getAccessToken('authorization_code', [
            'code' => $code,
            'scope' => ['READSYSTEM']
        ]);

        // Store token
        Storage::disk('token')->put('token.txt', json_encode($this->token->jsonSerialize()));
    }

    public function request(string $endpoint, array $params = [])
    {
        if ($this->token->hasExpired()) {
            $this->token = $this->refreshToken($this->token);
        }

        $request = $this->client->getAuthenticatedRequest(
            'GET',
            sprintf('https://api.nibeuplink.com/api/v1/%s', $endpoint),
            $this->token,
            $params
        );

        return (string) $this->client->getResponse($request)->getBody();
    }

    private function refreshToken($token) {
        $freshToken = $this->client->getAccessToken('refresh_token', [
            'refresh_token' => $token->getRefreshToken()
        ]);

        // Store token
        Storage::disk('token')->put('token.txt', json_encode($freshToken->jsonSerialize()));

        return $freshToken;
    }
}
