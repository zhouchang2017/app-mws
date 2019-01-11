<?php

namespace App\Models;

use App\Models\DP\ProductVariant;
use App\Observers\InventoryActionObserver;

/**
 * @property mixed type
 * @property mixed quantity
 */
class InventoryAction extends Model
{

    protected $fillable = [
        'product_id',
        'variant_id',
        'quantity',
        'warehouse_id',
        'warehouse_area',
        'type_id',
        'action_type_name',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(InventoryActionObserver::class);
    }


    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function type()
    {
        return $this->belongsTo(InventoryActionType::class);
    }

    public function origin()
    {
        return $this->morphTo();
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
