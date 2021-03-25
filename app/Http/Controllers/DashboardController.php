<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Khill\Lavacharts\Lavacharts;

use Lava;

class DashboardController extends Controller
{
    public function index()
    {
        $system = System::first();
        $system->load('parameters');

        $data = collect([
            'labels' => $system->parameters->unique('fetch_id')->pluck('created_at')->map(function($data) {
                return $data->format('Y-m-d H:i');
            })->toArray(),
            'values' => [
                'indoor' => $system->parameters->where('name', 'indoor_temperature')->pluck('value')->toArray(),
                'outdoor' => $system->parameters->where('name', 'outdoor_temperature')->pluck('value')->toArray(),
                'water' => $system->parameters->where('name', 'hot_water_temperature')->pluck('value')->toArray(),
            ]
        ]);

        return view('dashboard.index', [
            'data' => $data
        ]);
    }
}
