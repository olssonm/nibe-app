<?php

namespace App\Services\Nibe\Actions;

/**
 *
 */
trait ManagesSystemParameters
{
    public function getParameters(string $system, array $payload = [])
    {
        if (!count($payload)) {
            $payload = [
                'indoor_temperature',
                'outdoor_temperature',
                'hot_water_temperature',
                'fan_speed',
                'smart_temp_status'
            ];
        }

        $payload = http_build_query([
            'parameterIds' => $payload
        ]);

        return $this->get(sprintf('systems/%s/parameters?%s', $system, $payload));
    }
}
