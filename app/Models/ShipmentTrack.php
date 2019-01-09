<?php

namespace App\Models;

use App\Traits\MoneyFormatableTrait;

class ShipmentTrack extends Model
{
    use MoneyFormatableTrait;

    protected $fillable = [
        'logistic_id',
        'tracking_number',
        'price',
        'description',
    ];



    public function logistic()
    {
        return $this->belongsTo(Logistic::class);
    }
}
