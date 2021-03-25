<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fetch extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function parameters()
    {
        return $this->hasMany(Parameter::class);
    }
}
