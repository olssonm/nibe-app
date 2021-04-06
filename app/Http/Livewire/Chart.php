<?php

namespace App\Http\Livewire;

use App\Models\System;
use App\Repositories\ChartRepository;
use App\Services\Range;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class Chart extends Component
{
    public array $datapoints = [
        'indoor_temperature',
        'outdoor_temperature'
    ];

    public string $range = 'last_30_days';

    public System $system;

    public function mount(System $system)
    {
        $this->system = $system;
    }

    public function render(ChartRepository $chartRepository)
    {
        $range = new Range($this->range);

        $chartData = $chartRepository->compile($this->system, $range, $this->datapoints);

        $this->emit('refresh', $chartData);

        return view('livewire.chart', ['chartData' => $chartData]);
    }
}
