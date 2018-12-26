<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStatus\HasStatuses;

// 操作单(拣货单\入仓单)
class PreInventoryActionOrder extends Model
{
    use HasStatuses;

    protected $fillable = [
        'warehouse_id',
        'pre_inventory_action_id',
        'type_id',
        'description',
    ];


    const PENDING = 'pending'; // 等待分配库存
    const COMPLETED = 'completed'; // 分配完成

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
}
