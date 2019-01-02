<?php

namespace App\Models\DP;


class OrderItemUnit extends Model
{
    public function adjustments()
    {
        return $this->hasMany(Adjustment::class);
    }

    public function getTotalAttribute()
    {
        // TODO item.unit_price + adjustment_total
    }
}
