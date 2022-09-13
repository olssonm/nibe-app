<?php

namespace App\Repositories;

use App\Models\System;
use App\Services\Range;
use Carbon\Carbon;
use DB;
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
        $parameters = DB::table('parameters')
            ->join('fetches', function($join) use ($system) {
                $join->on('parameters.fetch_id', '=', 'fetches.id')
                    ->where('fetches.system_id', $system->id);
            })
            ->where(function ($q2) use ($range) {
                $q2->where('parameters.created_at', '>=', $range->getFrom())
                    ->where('parameters.created_at', '<=', $range->getTo());
            })
            ->select('parameters.id', 'fetch_id', 'name', DB::raw('avg(value) as avg_value, DATE_FORMAT(TIMESTAMP(created_at), "' . $range->getSqlFormat() . '") as formatted_date'))
            ->groupBy('name', 'formatted_date')
            ->get();

        $datasets = collect(array_map(function($name) use ($parameters) {
            return [
                'name' => config('nibe.parameters_shortname.' . $name),
                'values' => $parameters->where('name', $name)->pluck('avg_value')->map(function($value) use ($name) {
                    return round($this->formatValue($name, $value), 1);
                })->toArray()
            ];
        }, $datapoints));

        return collect([
            'datapoints' => count($parameters),
            'labels' => $parameters->unique('fetch_id')->pluck('formatted_date')->map(
                function ($data) use ($range) {
                    return Carbon::parse($data)->format($range->getFormat());
                }
            )->toArray(),
            'datasets' => $datasets
        ]);
    }

    private function formatValue($name, $value)
    {
        if (in_array($name, ['indoor_temperature', 'outdoor_temperature', 'hot_water_temperature', 'smart_temp_status'])) {
            return round($value / 10, 1);
        }

        return $value;
    }
}
