<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product_id',
        'variant_id',
        'warehouse_id',
        'warehouse_area',
        'quantity',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public static function updateByAction(InventoryAction $action)
    {
        $inventory = Inventory::where(
            [
                [ 'variant_id', $action->variant_id ],
                [ 'warehouse_id', $action->warehouse_id ],
                [ 'warehouse_area', $action->warehouse_area ],
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
                'warehouse_id'                  => $action->warehouse_id,
                'quantity'                      => $action->quantity,
                'warehouse_area'                => $action->warehouse_area,
            ]);
        }
    }
}
