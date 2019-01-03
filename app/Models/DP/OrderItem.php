<?php

namespace App\Models\DP;


use App\Traits\MoneyFormatableTrait;

class OrderItem extends Model
{
    use MoneyFormatableTrait;

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

    public function getUnitPrice()
    {
        $this->units->each->append('price');
        return $this;
    }

    public function getUnitsTotalAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

    public function getTotalAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

    public function getUnitPriceAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

}
