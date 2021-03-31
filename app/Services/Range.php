<?php

namespace App\Services;

use App\Models\Parameter;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Range
{
    /**
     * Start of range
     *
     * @var \Carbon\Carbon
     */
    public Carbon $from;

    /**
     * End of range
     *
     * @var \Carbon\Carbon
     */
    public Carbon $to;

    /**
     * Current format
     *
     * @var string
     */
    protected string $format = 'Y-m-d H:i';

    /**
     * Available formats
     *
     * @var array
     */
    protected array $formats = [
        'default' => 'Y-m-d H:i',
        'hourly' => 'Y-m-d H:00',
        'daily' => 'Y-m-d'
    ];

    /**
     * Constructor
     *
     * @param string $period
     */
    public function __construct(string $period = 'today')
    {
        $this->setRange($period);

        // Set the resolution/grouping based on the diff in days
        $diff = $this->from->diffInDays($this->to);
        if ($diff >= 32) {
            $this->format = $this->formats['daily'];
        } elseif ($diff >= 7) {
            $this->format = $this->formats['hourly'];
        }
    }

    /**
     * Get the start of range
     *
     * @return Carbon
     */
    public function getFrom(): Carbon
    {
        return $this->from;
    }

    /**
     * Get the end of range
     *
     * @return Carbon
     */
    public function getTo(): Carbon
    {
        return $this->to;
    }

    /**
     * Get current format
     *
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * Set the start and end of range
     *
     * @param string $period
     * @return void
     */
    protected function setRange(string $period): void
    {
        switch ($period) {
            case 'today':
                $this->from = now()->startOfDay();
                $this->to = now();
                break;

            case 'yesterday':
                $this->from = now()->subDay()->startOfDay();
                $this->to = now()->subDay()->endOfDay();
                break;

            case 'last_7_days':
                $this->from = now()->subDays(7)->startOfDay();
                $this->to = now();
                break;

            case 'last_30_days':
                $this->from = now()->subDays(30)->startOfDay();
                $this->to = now();
                break;

            case 'this_month':
                $this->from = now()->startOfMonth();
                $this->to = now();
                break;

            case 'last_month':
                $this->from = now()->subMonthNoOverflow()->startOfMonth();
                $this->to = now()->subMonthNoOverflow()->endOfMonth();
                break;

            case 'this_year':
                $this->from = now()->startOfYear();
                $this->to = now();
                break;

            case 'max':
                $this->from = Parameter::first()->created_at;
                $this->to = now();
                break;
        }
    }
}
