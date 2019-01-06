<?php

namespace App\Models\Divisions;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [ 'name' ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
