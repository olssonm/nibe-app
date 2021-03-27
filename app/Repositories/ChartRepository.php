<?php

namespace App\Repositories;

use App\Models\System;
use App\Services\Range;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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
    public function compile(System $system, Range $range, array $datapoints): Collection
    {
        $system->load(['parameters' => function ($q) use ($range, $datapoints) {
            $q->whereIn('name', $datapoints)
                ->where(function ($q2) use ($range) {
                    $q2->where('parameters.created_at', '>=', $range->getFrom())
                        ->where('parameters.created_at', '<=', $range->getTo());
                });
        }]);

        $parameters = $system->parameters->groupBy(function ($item) use ($range) {
            return $item->created_at->format($range->getFormat());
        })->map(function ($item) use ($datapoints) {
            return array_map(function ($value) use ($item) {
                return collect([
                    'name' => $value,
                    'value' => round($item->where('name', $value)->avg('value'), 1),
                    'fetch_id' => $item->first()->fetch_id,
                    'created_at' => Carbon::parse($item->first()->created_at)
                ]);
            }, $datapoints);
        })->flatten(1);

        $datasets = collect(array_map(function($value) use ($parameters) {
            return [
                'name' => config('nibe.parameters_shortname.' . $value),
                'values' => $parameters->where('name', $value)->pluck('value')->toArray()
            ];
        }, $datapoints));

        return collect([
            'labels' => $parameters->unique('fetch_id')->pluck('created_at')->map(
                function ($data) use ($range) {
                    return $data->format($range->getFormat());
                }
            )->toArray(),
            'datasets' => $datasets
        ]);
    }
}
