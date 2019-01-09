<?php

namespace App\Models;

use App\Models\DP\Product;
use App\Models\DP\ProductVariant;
use App\Scopes\SupplierInventoryScope;
use Illuminate\Database\Eloquent\Builder;

class Inventory extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'product_id',
        'variant_id',
        'warehouse_id',
        'warehouse_area',
        'quantity',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SupplierInventoryScope());
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

    public function supplier()
    {
        return $this->hasOne(SupplierVariant::class, 'variant_id', 'variant_id');
    }

    public function scopeFindWarehouseVariants($query, $warehouseId, $variantId, $productId = null)
    {
        return $query->where([
            ['warehouse_id', $warehouseId],
            ['variant_id', $variantId],
        ])->when($productId, function ($query, $productId) {
            return $query->where('product_id', $productId);
        });
    }

    public function scopeSearch(Builder $query)
    {
        return $query->when(request()->variant_id, function ($query, $variantId) {
            return $query->where('variant_id', $variantId);
        })->when(request()->warehouse_id, function ($query, $warehouseId) {
            return $query->where('warehouse_id', $warehouseId);
        });
    }


    public static function updateByAction(InventoryAction $action)
    {
        $inventory = Inventory::where(
            [
                ['variant_id', $action->variant_id],
                ['warehouse_id', $action->warehouse_id],
                ['warehouse_area', $action->warehouse_area],
            ]
        )->first();

        if ($inventory) {
            $action->type->isTake() ? // sub
                $inventory->decrement('quantity', $action->quantity) : // add
                $inventory->increment('quantity', $action->quantity);
        } else {
            Inventory::create([
                config('inventory.product_key') => $action->product_id ?? null,
                config('inventory.variant_key') => $action->variant_id,
                'warehouse_id' => $action->warehouse_id,
                'quantity' => $action->quantity,
                'warehouse_area' => $action->warehouse_area,
            ]);
        }
    }
}
