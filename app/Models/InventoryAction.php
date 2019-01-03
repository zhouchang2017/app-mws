<?php

namespace App\Models;

use App\Observers\InventoryActionObserver;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed type
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
}
