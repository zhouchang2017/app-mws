<?php

namespace App\Models;

use App\Models\DP\Product;
use App\Models\DP\ProductVariant;
use Illuminate\Database\Eloquent\Model;

class PreInventoryActionOrderItem extends Model
{
    protected $fillable = ['pre_order_id', 'product_id', 'variant_id', 'quantity'];

    // 操作单
    public function preOrder()
    {
        return $this->belongsTo(PreInventoryActionOrder::class);
    }

    // 对应产品
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 对应变体
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // 确认状态
    public function state()
    {
        return $this->hasMany(PreInventoryActionOrderItem::class, 'item_id');
    }

    // 已确认数量
    public function getConfirmCountAttribute()
    {
        return $this->state()->count();
    }
}
