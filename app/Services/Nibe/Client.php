<?php

namespace App\Services\Nibe;

use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Olssonm\OAuth2\Client\Provider\Nibe;
use App\Services\Nibe\Actions\ManagesSystemParameters;
use App\Services\Nibe\Actions\ManagesSystems;
use Storage;

/**
 * Nibe client
 */
class Client
{
    use ManagesSystemParameters,
        ManagesSystems,
        MakesHttpRequests;

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
        // If token is present
        if ($this->tokenExists()) {
            $data = Storage::disk('token')->get('token.txt');
            $this->token = new AccessToken(json_decode($data, true));
        }

        $params = array_merge(
            config('services.nibe'),
            ['redirectUri' => route('auth.callback')],
            $params
        );

        $this->client = new Nibe($params);
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
        $this->token = $this->client->getAccessToken('authorization_code', [
            'code' => $code
        ]);

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

    /**
     * Refresh the current token
     *
     * @return void
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    private function refreshToken(): void
    {
        $freshToken = $this->client->getAccessToken('refresh_token', [
            'refresh_token' => $this->token->getRefreshToken()
        ]);

        // Store token
        Storage::disk('token')->put('token.txt', json_encode($freshToken->jsonSerialize()));

        $this->token = $freshToken;
    }
}
