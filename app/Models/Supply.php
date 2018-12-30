<?php

namespace App\Models;

use App\Events\SupplyApprovedEvent;
use App\Notifications\SupplyApprovedNotification;
use App\Notifications\SupplyPendingNotification;
use App\Notifications\SupplyUnShipNotification;
use App\Observers\SupplyObserver;
use App\Scopes\WithStateScope;
use App\Traits\HasStatuses;
use App\Traits\TrackableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

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

    protected $fillable = [ 'description', 'total', 'has_ship' ]; // has_ship 是否需要物流

    protected $appends = [ 'current_state' ];

    protected $casts = [
        'has_ship' => 'bool',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(SupplyObserver::class);
        static::addGlobalScope(new WithStateScope());
    }


    public function getCurrentStateAttribute()
    {
        $status = [
            self::UN_COMMIT    => '未提交',
            self::PENDING      => '已提交(审核中)',
            self::APPROVED     => '审核通过,等待分配接收仓库',
            self::UN_SHIP      => '待发货',
            self::PART_SHIPPED => '部分发货',
            self::SHIPPED      => '已发货',
            self::COMPLETED    => '已完成',
            self::CANCEL       => '已取消',
        ];
        return array_get($status, $this->state->name, 'N/A');
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

    public function canShowOrders()
    {
        return tap(in_array($this->status, [
            static::UN_SHIP,
            static::PART_SHIPPED,
            static::SHIPPED,
            static::COMPLETED,
        ]), function ($res) {
            if ($res) {
                $this->loadOrders();
            }
        });
    }

    public function loadOrders()
    {
        $this->loadMissing(['preInventoryAction.orders']);
        $this->preInventoryAction->orders->each->loadDetailAttribute();
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
