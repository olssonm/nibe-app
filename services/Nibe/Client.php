<?php

namespace Services\Nibe;

use League\OAuth2\Client\Token\AccessToken;
use Olssonm\OAuth2\Client\Provider\Nibe;
use Services\Nibe\Actions\ManagesSystemParameters;
use Services\Nibe\Actions\ManagesSystems;
use Storage;

class Client
{
    use ManagesSystemParameters,
        ManagesSystems,
        MakesHttpRequests;

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
            'redirectUri' => route('authorize.capture')
        ]);
    }

    public function getAuthorizationUrl(): string
    {
        return $this->client->getAuthorizationUrl();
    }

    public function setAccessToken(string $code)
    {
        $this->token = $this->client->getAccessToken('authorization_code', [
            'code' => $code
        ]);

        // Store token
        Storage::disk('token')->put('token.txt', json_encode($this->token->jsonSerialize()));
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
