<?php

namespace App\Models;

use App\Models\DP\Product;
use App\Models\DP\ProductVariant;
use App\Traits\MoneyFormatableTrait;

/**
 * @property mixed variant
 */
class OrderItem extends Model
{
    use MoneyFormatableTrait;

    protected $fillable = ['quantity', 'variant_id', 'product_id', 'origin_price', 'price', 'rest'];

    protected $casts = [
        'rest' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getOriginPriceAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

    public function setOriginPriceAttribute($value)
    {
        $this->attributes['origin_price'] = $value;
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value;
    }
}
