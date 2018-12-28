<?php

namespace App\Models;

use App\Observers\PreInventoryActionObserver;
use App\Traits\PreInventoryActionStatusTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStatus\HasStatuses;

// 预出\入库(入库单\出货单)

/**
 * @property mixed origin
 */
class PreInventoryAction extends Model
{
    use HasStatuses, PreInventoryActionStatusTrait;

    protected $fillable = ['description', 'type_id'];

    /*
     * 审核通过后，等待管理员填写操作单
     * */
    const PENDING = 'pending';   // 等待审核
    const APPROVED = 'approved'; // 审核通过
    const REJECTED = 'rejected'; // 拒绝
    const ASSIGNED = 'assigned'; // 以分配库存，生成操作单

    protected static function boot()
    {
        parent::boot();
        static::observe(PreInventoryActionObserver::class);
    }


    // 来源
    public function origin()
    {
        return $this->morphTo();
    }

    // 是否需要物流
    public function transport()
    {
        return $this->origin->transport();
    }

    public function type()
    {
        return $this->belongsTo(InventoryActionType::class);
    }

    // 通过来源提供是产品列表产生操作单

    // 操作单
    public function orders()
    {
        return $this->hasMany(PreInventoryActionOrder::class, 'pre_inventory_action_id');
    }

    // 详情
    public function scopeWithOrders($query)
    {
        return $this->with(['orders.warehouse', 'orders.items.variant', 'orders.tracks.logistic']);
    }

}
