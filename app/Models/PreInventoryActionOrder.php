<?php

namespace App\Models;

use App\Traits\TrackableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStatus\HasStatuses;

// 操作单(拣货单\入仓单)

/**
 * @property mixed preInventoryAction
 * @property mixed warehouse
 */
class PreInventoryActionOrder extends Model
{
    use HasStatuses, TrackableTrait;

    protected $fillable = [
        'warehouse_id',
        'pre_inventory_action_id',
        'type_id',
        'description',
    ];

    const UN_SHIP = 'UN_SHIP'; // 待发货
    const SHIPPED = 'SHIPPED'; // 已发货

    // 入库仓库
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // 预出\入库(入库单\出货单)
    public function preInventoryAction()
    {
        return $this->belongsTo(PreInventoryAction::class);
    }

    // 操作类型 take/put
    public function type()
    {
        return $this->belongsTo(InventoryActionType::class);
    }

    public function items()
    {
        return $this->hasMany(PreInventoryActionOrderItem::class, 'pre_order_id');
    }

    // 是否需要物流
    public function transport()
    {
        return $this->preInventoryAction->transport();
    }

    public function loadDetailAttribute()
    {
        $this->loadMissing(['warehouse', 'items.variant', 'items.tracks', 'tracks']);
        return $this;
    }

    public function appendWarehouseSimpleAddress()
    {
        $this->warehouse->append('simple_address');
        return $this;
    }


    public function toShipment($data, $fillItem = false)
    {
        return tap($this->shipment($data), function ($shipment) use ($fillItem) {
            if ($fillItem) {
                $this->items->each->shipment($shipment);
            }
        });
    }
}
