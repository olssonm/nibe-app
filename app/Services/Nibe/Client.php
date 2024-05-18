<?php

namespace App\Services\Nibe;

use League\OAuth2\Client\Token\AccessToken;
use App\Services\Nibe\Actions\ManagesSystemParameters;
use App\Services\Nibe\Actions\ManagesSystems;
use League\OAuth2\Client\Provider\GenericProvider;
use Storage;

/**
 * Nibe client
 */
class Client
{
    use ManagesSystemParameters;
    use ManagesSystems;
    use MakesHttpRequests;

    /**
     * @var \League\OAuth2\Client\Token\AccessTokenInterface
     */
    protected $token;

    /**
     * @var \Olssonm\OAuth2\Client\Provider\Nibe;
     */
    protected $client;

    public function __construct(array $params = [])
    {
        $params = array_merge(
            config('services.nibe'),
            [
                'redirectUri'             => '',
                'urlAuthorize'            => '',
                'urlAccessToken'          => 'https://api.myuplink.com/oauth/token',
                'urlResourceOwnerDetails' => ''
            ]
        );

        $this->client = new GenericProvider($params);

        // If token is present
        if ($this->tokenExists()) {
            $data = Storage::disk('token')->get('token.txt');
            $this->token = new AccessToken(json_decode($data, true));
        }

        if (!$this->tokenExists() || $this->token->hasExpired()) {
            return $this->setAccessToken('');
        }
    }

    /**
     * Get the URL used for oauth-authorization
     *
     * @param array $params
     * @return string
     */
    public function getAuthorizationUrl(array $params = []): string
    {
        return $this->client->getAuthorizationUrl($params);
    }

    /**
     * Set (and store) the token to be used
     *
     * @param string $code
     * @return void
     */
    public function setAccessToken(string $code): void
    {
        $this->token = $this->client->getAccessToken('client_credentials');

        // Store token
        Storage::disk('token')->put('token.txt', json_encode($this->token->jsonSerialize()));
    }

    /**
     * Check if the token exists/has been set
     *
     * @return boolean
     */
    public function tokenExists(): bool
    {
        return Storage::disk('token')->has('token.txt');
    }
}
