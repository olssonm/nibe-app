<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $dates = [
        'created_at',
    ];

    public function fetch()
    {
        return $this->belongsTo(Fetch::class);
    }

    public function getValueAttribute()
    {
        $attribute = $this->attributes['value'];

        if (in_array($this->name, ['indoor_temperature', 'outdoor_temperature', 'hot_water_temperature'])) {
            return round($attribute / 10, 1);
        }

        return $attribute;
    }
}
