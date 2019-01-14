<?php

namespace App\Models\DP;


class ProductImage extends Model
{
    public $fillable = ['product_id', 'image', 'locale_code'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
