<?php

namespace App\Models;

use App\Traits\MoneyFormatableTrait;
use Illuminate\Database\Eloquent\Model;

class ShipmentTrack extends Model
{
    use MoneyFormatableTrait;

    protected $fillable = [
        'logistic_id',
        'tracking_number',
        'price',
        'description',
    ];

    public function trackable()
    {
        return $this->morphTo();
    }

    public function logistic()
    {
        return $this->belongsTo(Logistic::class);
    }
}
