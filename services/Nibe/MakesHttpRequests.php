<?php

namespace Services\Nibe;

/**
 *
 */
trait MakesHttpRequests
{
    protected $baseEndpoint = 'https://api.nibeuplink.com/api/v1/%s';

    public function get($uri)
    {
        return $this->request('GET', $uri);
    }

    public function request(string $verb, string $endpoint, array $payload = [])
    {
        if ($this->token->hasExpired()) {
            $this->token = $this->refreshToken($this->token);
        }

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
