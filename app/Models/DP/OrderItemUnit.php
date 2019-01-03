<?php

namespace App\Models\DP;


use App\Traits\MoneyFormatableTrait;

/**
 * @property mixed adjustments_total
 */
class OrderItemUnit extends Model
{
    use MoneyFormatableTrait;

    public function adjustments()
    {
        return $this->hasMany(Adjustment::class);
    }

    public function item()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function getTotalAttribute()
    {
        // TODO item.unit_price + adjustment_total
    }

    public function getPriceAttribute()
    {
        return $this->item->unit_price + $this->adjustments_total;
    }

    public function getAdjustmentsTotalAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

}
