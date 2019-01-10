<?php

namespace App\Models;


use App\Models\DP\Product;
use App\Models\DP\ProductVariant;

class WithdrawItem extends Model
{
    protected $fillable = ['warehouse_id', 'warehouse_area', 'product_id', 'variant_id', 'quantity'];

    protected $with = ['warehouse'];

    public function withdraw()
    {
        return $this->belongsTo(Withdraw::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
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
