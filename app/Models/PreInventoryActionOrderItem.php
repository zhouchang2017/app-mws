<?php

namespace App\Models;

use App\Models\DP\Product;
use App\Models\DP\ProductVariant;
use App\Traits\TrackableTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed preOrder
 * @property mixed quantity
 * @property mixed variant_id
 */
class PreInventoryActionOrderItem extends Model
{
    use TrackableTrait;

    protected $fillable = [ 'pre_order_id', 'product_id', 'variant_id', 'quantity' ];

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
        return $this->hasMany(PreInventoryActionOrderItemState::class, 'item_id');
    }

    public function loadState()
    {
        $this->loadMissing([ 'state.type' ]);
        return $this;
    }

    /**
     * 入库
     * @param $quantity
     * @param $warehouseArea
     * @return PreInventoryActionOrderItemState
     * @throws \Exception
     */
    public function addCheck($quantity, $warehouseArea)
    {
        if ($quantity + $this->getConfirmCountAttribute() <= $this->quantity) {
            return $this->state()->create([
                'quantity'       => $quantity,
                'warehouse_area' => $warehouseArea,
                'type_id'        => $this->preOrder->type->id,
                'variant_id'     => $this->variant_id
            ]);
        } else {
            throw new \Exception('超出实际数量');
        }
    }

    // 已确认数量
    public function getConfirmCountAttribute()
    {
        return $this->state()->sum('quantity');
    }

    // 是否需要物流
    public function transport()
    {
        return $this->preOrder->transport();
    }


}
