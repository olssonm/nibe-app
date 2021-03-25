<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fetches()
    {
        return $this->hasMany(Fetch::class);
    }

    public function parameters()
    {
        return $this->hasManyThrough(Parameter::class, Fetch::class)
            ->orderBy('created_at', 'ASC');
    }
}
