<?php

namespace App\Repositories;

use App\Models\System;
use App\Services\Range;
use Carbon\Carbon;
use DB;
use Debugbar;
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
        Debugbar::startMeasure('render', 'Time for rendering');
        Debugbar::startMeasure('database', 'Time for database');

        $parameters = DB::table('parameters')
            ->whereIn('fetch_id', function ($query) use ($system) {
                $query->select('fetches.id')
                    ->from('fetches')
                    ->where('system_id', $system->id);
            })
            ->whereIn('parameters.name', $datapoints)
            ->where(function($q) use ($range) {
                $q->where(function ($q2) use ($range) {
                    $q2->where('parameters.created_at', '>=', $range->getFrom())
                        ->where('parameters.created_at', '<=', $range->getTo());
                });
            })
            ->groupByRaw('date_formated, name')
            ->select(
                'parameters.id',
                'parameters.name',
                'parameters.value',
                'parameters.fetch_id',
                'parameters.created_at',
            )
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date_formated')
            ->get();


        $parameters = $parameters->groupBy(function ($item) use ($range) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        });

        $parameters->map(function ($item) use ($datapoints) {
            return array_map(function ($value) use ($item) {
                return collect([
                    'name' => $value,
                    'value' => round($item->where('name', $value)->avg('value'), 1),
                    'fetch_id' => $item->first()->fetch_id,
                    'created_at' => Carbon::parse($item->first()->created_at)
                ]);
            }, $datapoints);
        })->flatten(1);

        // dd($parameters);

        // $system->load(['parameters' => function ($q) use ($range, $datapoints) {
        //     $q->whereIn('name', $datapoints)
        //         ->where(function ($q2) use ($range) {
        //             $q2->where('parameters.created_at', '>=', $range->getFrom())
        //                 ->where('parameters.created_at', '<=', $range->getTo());
        //         });
        // }]);

        // Debugbar::stopMeasure('database');

        // $parameters = $system->parameters->groupBy(function ($item) use ($range) {
        //     return $item->created_at->format($range->getFormat());
        // })->map(function ($item) use ($datapoints) {
        //     return array_map(function ($value) use ($item) {
        //         return collect([
        //             'name' => $value,
        //             'value' => round($item->where('name', $value)->avg('value'), 1),
        //             'fetch_id' => $item->first()->fetch_id,
        //             'created_at' => Carbon::parse($item->first()->created_at)
        //         ]);
        //     }, $datapoints);
        // })->flatten(1);

        $datasets = collect(array_map(function($value) use ($parameters) {
            return [
                'name' => config('nibe.parameters_shortname.' . $value),
                'values' => $parameters->where('name', $value)->pluck('value')->toArray()
            ];
        }, $datapoints));

        // Debugbar::stopMeasure('render');

        return collect([
            'datapoints' => count($parameters),
            'labels' => $parameters->unique('fetch_id')->pluck('created_at')->map(
                function ($data) use ($range) {
                    return Carbon::parse($data)->format($range->getFormat());
                }
            )->toArray(),
            'datasets' => $datasets
        ]);
    }
}
