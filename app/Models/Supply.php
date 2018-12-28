<?php

namespace App\Models;

use App\Events\SupplyApprovedEvent;
use App\Notifications\SupplyApprovedNotification;
use App\Notifications\SupplyPendingNotification;
use App\Notifications\SupplyUnShipNotification;
use App\Observers\SupplyObserver;
use App\Traits\TrackableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Spatie\ModelStatus\HasStatuses;

/**
 * @property mixed origin
 * @property mixed preInventoryAction
 * @property mixed has_ship
 * @property mixed items
 * @property mixed state
 */
class Supply extends Model
{
    use HasStatuses, TrackableTrait;

    const UN_COMMIT = 'UN_COMMIT'; // 未提交(保存)
    const PENDING = 'PENDING';   // 待审核(提交审核)
    const APPROVED = 'APPROVED'; // 审核通过 等待分配接收仓库
    const UN_SHIP = 'UN_SHIP';  // 待发货
    const PART_SHIPPED = 'PART_SHIPPED';  // 部分发货
    const SHIPPED = 'SHIPPED';  // 已发货
    const COMPLETED = 'COMPLETED'; // 已完成
    const CANCEL = 'CANCEL'; // 取消

    protected $fillable = ['description', 'total', 'has_ship']; // has_ship 是否需要物流

    protected $appends = ['current_state'];

    protected static function boot()
    {
        parent::boot();
        static::observe(SupplyObserver::class);
        static::addGlobalScope('withState',function(Builder $builder){
            $builder->with('state');
        });
    }

    public function scopeWithStatus(Builder $builder)
    {
        $builder->with('state');
    }

    public function getCurrentStateAttribute()
    {
        $status = [
            self::UN_COMMIT => '未提交',
            self::PENDING => '待审核(提交审核)',
            self::APPROVED => '审核通过,等待分配接收仓库',
            self::UN_SHIP => '待发货',
            self::PART_SHIPPED => '部分发货',
            self::SHIPPED => '已发货',
            self::COMPLETED => '已完成',
            self::CANCEL => '已取消',
        ];
        return array_get($status, $this->state->name, 'N/A');
    }

    public function state()
    {
        return $this->morphOne($this->getStatusModelClassName(), 'model', 'model_type', $this->getModelKeyColumnName())
            ->latest('id');
    }


    /**
     * 运输方式 true=物流 false=无需物流
     * @return bool
     */
    public function transport()
    {
        return $this->has_ship ? true : false;
    }

    // 供货计划来源
    public function origin()
    {
        return $this->morphTo();
    }

    /*
     * 供货明细
     * */
    public function items()
    {
        return $this->hasMany(SupplyItem::class);
    }

    // 预出\入库(入库单\出货单)
    public function preInventoryAction()
    {
        return $this->morphOne(PreInventoryAction::class, 'origin');
    }

    public function notify(Notification $notification)
    {
        $this->origin->admin->notify($notification);
    }

    // 审核通过通知
    public function approvedNotify()
    {
        $this->latestStatus(Supply::PENDING)->user->notify(new SupplyApprovedNotification($this));
    }

    // 预出\入库(入库单\出货单)已生成 等待发货通知
    public function unShipNotify()
    {
        $this->latestStatus(Supply::PENDING)->user->notify(new SupplyUnShipNotification($this));
    }

    // 提交审核通知
    public function pendingNotify()
    {
        $this->origin->admin->notify(new SupplyPendingNotification($this));
    }
}
