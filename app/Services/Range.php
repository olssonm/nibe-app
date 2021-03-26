<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Range
{
    public string $format = 'Y-m-d H:i';

    public string $range;

    public Carbon $from;

    public Carbon $to;

    protected array $formats = [
        'default' => 'Y-m-d H:i',
        'hourly' => 'Y-m-d H:00',
        'daily' => 'Y-m-d'
    ];

    public function __construct(string $range = 'today', string $from = null, string $to = null)
    {
        $this->range = $range;

        $method = Str::camel($range);

        if(method_exists($this, $method) && $method !== 'custom') {
            $this->$method();
        } elseif($method == 'custom') {
            $this->$method($from, $to);
        }

        $diff = $this->from->diffInDays($this->to);
        if ($diff >= 32) {
            $this->format = $this->formats['daily'];
        } elseif ($diff >= 7) {
            $this->format = $this->formats['hourly'];
        }
    }

    private function today()
    {
        $this->from = now()->startOfDay();
        $this->to = now();
    }

    private function yesterday()
    {
        $this->from = now()->subDay()->startOfDay();
        $this->to = now()->subDay()->endOfDay();
    }

    private function last7Days()
    {
        $this->from = now()->subDays(7)->startOfDay();
        $this->to = now();
    }

    private function last30Days()
    {
        $this->from = now()->subDays(30)->startOfDay();
        $this->to = now();
    }

    private function thisMonth()
    {
        $this->from = now()->startOfMonth();
        $this->to = now();
    }

    private function lastMonth()
    {
        $this->from = now()->subMonth()->startOfMonth();
        $this->to = now()->subMonth()->endOfMonth();
    }

    private function thisYear()
    {
        $this->from = now()->startOfYear();
        $this->to = now();
    }

    private function custom(string $from, string $to)
    {
        $this->from = Carbon::parse($from);
        $this->to = Carbon::parse($to);
    }
}
