<?php

namespace App\Models\Divisions;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [ 'name', 'city_id', 'province_id' ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
