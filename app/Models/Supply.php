<?php

namespace App\Models;

use App\Notifications\SupplyPendingNotification;
use App\Notifications\SupplyUnShipNotification;
use App\Observers\SupplyObserver;
use App\Traits\TrackableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Spatie\ModelStatus\HasStatuses;

/**
 * @property mixed origin
 */
class Supply extends Model
{
    use HasStatuses, TrackableTrait;

    const UN_COMMIT = 'UN_COMMIT'; //未提交(保存)
    const PENDING = 'PENDING';  //待审核(提交审核)
    const UN_SHIP = 'UN_SHIP';  //待发货
    const PART_SHIPPED = 'PART_SHIPPED';  //部分发货
    const SHIPPED = 'SHIPPED';  //已发货
    const COMPLETED = 'COMPLETED'; //已完成
    const CANCEL = 'CANCEL'; //取消

    protected $fillable = ['description', 'total', 'has_ship'];

    protected static function boot()
    {
        parent::boot();
        static::observe(SupplyObserver::class);
    }


    // 供货计划来源
    public function origin()
    {
        return $this->morphTo();
    }

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
    public function unShipNotify()
    {
        $this->origin->latestStatus(Supply::PENDING)->user->notify(new SupplyUnShipNotification($this));
    }

    // 提交审核通知
    public function pendingNotify()
    {
        $this->origin->admin->notify(new SupplyPendingNotification($this));
    }
}
