<?php

namespace App\Models;

use App\Observers\PreInventoryActionOrderItemStateObserver;
use Illuminate\Database\Eloquent\Model;

class PreInventoryActionOrderItemState extends Model
{
    protected $fillable = [ 'warehouse_area', 'type_id', 'quantity' ];

    protected static function boot()
    {
        parent::boot();
        static::observe(PreInventoryActionOrderItemStateObserver::class);
    }

    public function item()
    {
        return $this->belongsTo(PreInventoryActionOrderItem::class);
    }

    public function type()
    {
        return $this->belongsTo(InventoryActionType::class);
    }
}
