<?php

namespace App\Repositories;

use App\Models\System;
use App\Services\Range;
use Carbon\Carbon;

class ChartRepository
{
    /**
     * Compile the data to a usable format for the charts.
     * This method also handles the grouping-resolutions to avoid
     * overpopulating the graph
     *
     * @param System $system
     * @param Range $range
     * @param array $datapoints
     * @return \Illuminate\Support\Collection
     */
    public function compile(System $system, Range $range, array $datapoints)
    {
        $system->load(['parameters' => function ($q) use ($range, $datapoints) {
            $q->whereIn('name', $datapoints)
                ->where(function ($q2) use ($range) {
                    $q2->where('parameters.created_at', '>=', $range->from)
                        ->where('parameters.created_at', '<=', $range->to);
                });
        }]);

        $system->parameters = $system->parameters->groupBy(function ($item) use ($range) {
            return $item->created_at->format($range->format);
        })->map(function ($item) use ($datapoints) {
            $data = [];
            foreach ($datapoints as $value) {
                $data[] = collect([
                    'name' => $value,
                    'value' => round($item->where('name', $value)->avg('value'), 1),
                    'fetch_id' => $item->first()->fetch_id,
                    'created_at' => Carbon::parse($item->first()->created_at)
                ]);
            }
            return $data;
        })->flatten(1);

        $datasets = [];
        foreach ($datapoints as $value) {
            $datasets[] = [
                'name' => config('nibe.parameters_shortname.' . $value),
                'values' => $system->parameters->where('name', $value)->pluck('value')->toArray()
            ];
        }

        $data = collect([
            'labels' => $system->parameters->unique('fetch_id')->pluck('created_at')->map(
                function ($data) use ($range) {
                    return $data->format($range->format);
                }
            )->toArray(),
            'datasets' => $datasets
        ]);

        return $data;
    }
}
