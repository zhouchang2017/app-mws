<?php

namespace App\Models;

use App\Observers\PreInventoryActionObserver;
use App\Scopes\WithStateScope;
use App\Traits\HasStatuses;
use App\Traits\PreInventoryActionStatusTrait;

// 预出\入库(入库单\出货单)

/**
 * @property mixed origin
 */
class PreInventoryAction extends Model
{
    use HasStatuses, PreInventoryActionStatusTrait;

    protected $fillable = ['description', 'type_id'];

    protected $appends = ['current_state'];

    protected $appendAuthorizes = ['approve','assign'];

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
        static::addGlobalScope(new WithStateScope());
    }

    public function getCurrentStateAttribute()
    {
        $status = [
            self::PENDING => '等待审核',
            self::APPROVED => '审核通过,等待分配仓库',
            self::REJECTED => '拒绝',
            self::ASSIGNED => '以分配库存',
        ];
        return array_get($status, $this->state->name, 'N/A');
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

    public function scopeWithType($query)
    {
        $query->with('type');
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

    public function loadOrders()
    {
        return tap($this, function ($model) {
            $model->loadMissing(['orders.warehouse', 'orders.items.variant', 'orders.tracks.logistic']);
        });
    }

    public function loadStatuses()
    {
        return tap($this, function ($model) {
            $model->loadMissing(['statuses.user']);
        });
    }

    public function loadOriginItems()
    {
        return tap($this, function ($model) {
            $model->loadMissing(['origin.items.variant']);
        });
    }

    public function loadType()
    {
        return tap($this, function ($model) {
            $model->loadMissing(['type']);
        });
    }


}
