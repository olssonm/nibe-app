<?php

namespace App\Services\Nibe\Actions;

/**
 *
 */
trait ManagesSystemParameters
{
    /**
     * Get the system parameters
     *
     * @param string $system
     * @param array $payload
     * @return stdObj
     */
    public function getParameters(string $system, array $payload = [])
    {
        if (!count($payload)) {
            $payload = [
                '40004', // Outdoor temperature
                '40013', // Hotwater temperature
                '40033', // Indoor temperature
            ];
        }

        $payload = http_build_query([
            'parameters' => implode(',', $payload)
        ]);

        return $this->get(sprintf('devices/%s/points?%s', $system, $payload));
    }
}
