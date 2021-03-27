<?php

namespace App\Console\Commands;

use App\Models\Fetch;
use App\Models\Parameter;
use App\Models\System;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Storage;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nibe:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import an CSV-export from Nibe Uplink';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $system = System::first();
        if (!$system) {
            return $this->error('No system set. Please setup the application first');
        }

        // Fetch file
        if (!Storage::has('import/historyexport.csv')) {
            return $this->error('/storage/app/import/historyexport.csv not found');
        }

        $defaultFan = $this->ask('What is your default fan-speed? (Default 37)', 37);

        $file = Storage::path('import/historyexport.csv');

        $data = array_map(function ($row) {
            // If first character is ";", we know that it is the header
            // which used a differect encoding
            if ($row[0] == ';') {
                $encoding = 'UTF-16LE';
            } else {
                $encoding = 'UTF-16';
            }
            $row = mb_convert_encoding($row, 'UTF-8', $encoding);
            return str_getcsv($row, ";");
        }, file($file));

        // Map fields
        $fields = [];
        foreach ($data[0] as $key => $value) {
            if (Str::contains($value, 'BT7')) {
                $fields[$key] = 'hot_water_temperature';
            } elseif (Str::contains($value, 'BT1')) {
                $fields[$key] = 'outdoor_temperature';
            } elseif (Str::contains($value, 'BT50')) {
                $fields[$key] = 'indoor_temperature';
            }
        }
        $fields[] = 'fan_speed';

        // Finally, do the import
        for ($i = 1; $i < count($data); $i++) {
            $fetch = Fetch::create([
                'system_id' => $system->id
            ]);

            foreach ($fields as $key => $name) {
                // Assume bad row if count is wrong
                if (count($data[$i]) < count($fields)) {
                    continue;
                }

                if (isset($data[$i][$key]) && !is_numeric($data[$i][$key])) {
                    continue;
                }

                $value = ($name == 'fan_speed') ? $defaultFan : $data[$i][$key] * 10;

                Parameter::create([
                    'fetch_id' => $fetch->id,
                    'name' => $name,
                    'value' => $value,
                    'created_at' => $data[$i][0] // Date field is always at "0"
                ]);
            }
        }

        return $this->info('Import complete!');
    }
}
