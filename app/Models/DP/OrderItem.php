<?php

namespace App\Models\DP;


class OrderItem extends Model
{
    protected $casts = [
        'option_values' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function units()
    {
        return $this->hasMany(OrderItemUnit::class, 'item_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function adjustments()
    {
        return $this->hasMany(Adjustment::class);
    }
}
