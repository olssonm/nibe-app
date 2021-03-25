<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Range
{
    public $range;

    public $from;

    public $to;

    public function __construct(string $range = 'today', string $from = null, string $to = null)
    {
        $this->range = $range;

        $method = Str::camel($range);

        if(method_exists($this, $method) && $method !== 'custom') {
            $this->$method();
        } elseif($method == 'custom') {
            $this->$method($from, $to);
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
