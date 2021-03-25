<?php

namespace App\Console\Commands;

use App\Models\Fetch as FetchModel;
use App\Models\Parameter;
use App\Models\System;
use Illuminate\Console\Command;
use App\Services\Nibe\Client;

class Fetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nibe:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store parameters';

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

        $fetch = FetchModel::create([
            'system_id' => $system->id
        ]);

        $client = new Client();
        $data = $client->getParameters($system->system_id);

        foreach ($data as $datum) {
            Parameter::create([
                'name' => $datum->name,
                'unit' => $datum->unit,
                'value' => $datum->rawValue,
                'fetch_id' => $fetch->id
            ]);
        }

        return $this->info(sprintf('Fetched parameters @ %s', now()->format('Y-m-d H:i:s')));
    }
}
