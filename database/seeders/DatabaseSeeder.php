<?php

namespace Database\Seeders;

use App\Models\Fetch;
use App\Models\Parameter;
use App\Models\System;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // If no system exists, create it
        $system = System::firstOrCreate([
            'id' => 1
        ], [
            'system_id' => 'xxx',
            'name' => 'F730 CU 3x400V',
            'product' => 'NIBE F730',
            'serial_number' => 'xxx'
        ]);

        // We do not use factories as they are not well suited for periods/sequences as this
        $start = now()->subDays(70);
        $end = now();

        while ($start->lte($end)) {
            $fetch = Fetch::create([
                'system_id' => $system->id
            ]);

            Parameter::insert([
                [
                    'fetch_id' => $fetch->id,
                    'name' => 'fan_speed',
                    'value' => 37,
                    'created_at' => $start
                ],
                [
                    'fetch_id' => $fetch->id,
                    'name' => 'hot_water_temperature',
                    'value' => $this->fakeTemperature($start, 40),
                    'created_at' => $start
                ],
                [
                    'fetch_id' => $fetch->id,
                    'name' => 'indoor_temperature',
                    'value' => $this->fakeTemperature($start, 21),
                    'created_at' => $start
                ],
                [
                    'fetch_id' => $fetch->id,
                    'name' => 'outdoor_temperature',
                    'value' => $this->fakeTemperature($start),
                    'created_at' => $start
                ],
            ]);

            $start->addMinutes(30);
        }
    }

    public function fakeTemperature($date, $basetemp = null)
    {
        if (!$basetemp) {
            $basetemp = [
                '−1.5',
                '−1.4',
                '1.4',
                '1.4',
                '6.6',
                '11.6',
                '14.7',
                '17.3',
                '16.3',
                '12.8',
                '7.5',
                '3.7',
                '0.6'
            ][$date->format('n')];

            $basetemp = (float) $basetemp;

            $time = $date->format('G');
            if ($time >= 20 && $time <= 8) {
                $basetemp -= rand(1, 3);
            } elseif ($time >= 12 && $time <= 18) {
                $basetemp += rand(1, 3);
            }
        } else {
            $basetemp += rand(-1, 5);
        }

        return $basetemp * 10;
    }
}
