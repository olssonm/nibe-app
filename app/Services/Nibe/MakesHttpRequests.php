<?php

namespace App\Services\Nibe;

/**
 *
 */
trait MakesHttpRequests
{
    protected $baseEndpoint = 'https://api.myuplink.com/v2/%s';

    public function get($uri)
    {
        return $this->request('GET', $uri);
    }

    /**
     * Main request, automatically handles refreshing of tokens
     *
     * @param string $verb
     * @param string $endpoint
     * @param array $payload
     * @return stdObj
     */
    public function request(string $verb, string $endpoint, array $payload = [])
    {
        // if ($this->token->hasExpired()) {
        //     $this->refreshToken($this->token);
        // }

        $request = $this->client->getAuthenticatedRequest(
            $verb,
            sprintf($this->baseEndpoint, $endpoint),
            $this->token,
            empty($payload) ? [] : $payload
        );

        $response = (string) $this->client->getResponse($request)->getBody();

        return json_decode($response, false) ?: $response;
    }
}
