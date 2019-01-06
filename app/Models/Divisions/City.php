<?php

namespace App\Models\Divisions;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [ 'name', 'province_id' ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
