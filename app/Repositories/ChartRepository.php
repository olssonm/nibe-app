<?php

namespace App\Repositories;

use App\Models\System;
use App\Services\Range;

class ChartRepository
{
    public function compile(System $system, Range $range, array $datapoints)
    {
        $system->load(['parameters' => function($q) use ($range, $datapoints) {
            $q->whereIn('name', $datapoints)
                ->where(function($q2) use ($range) {
                    $q2->where('parameters.created_at', '>=', $range->from)
                        ->where('parameters.created_at', '<=', $range->to);
                });
        }]);

        $datasets = [];
        foreach ($datapoints as $value) {
            $datasets[] = [
                'name' => config('nibe.parameters_shortname.' . $value),
                'values' => $system->parameters->where('name', $value)->pluck('value')->toArray()
            ];
        }

        $data = collect([
            'labels' => $system->parameters->unique('fetch_id')->pluck('created_at')->map(function ($data) {
                return $data->format('Y-m-d H:i');
            })->toArray(),
            'datasets' => $datasets
        ]);

        return $data;
    }
}
