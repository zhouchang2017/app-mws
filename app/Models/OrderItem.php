<?php

namespace App\Models;

use App\Models\DP\Product;
use App\Models\DP\ProductVariant;
use App\Traits\MoneyFormatableTrait;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use MoneyFormatableTrait;

    protected $fillable = ['quantity', 'variant_id', 'product_id','price'];

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
}
