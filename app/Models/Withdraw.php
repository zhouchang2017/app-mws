<?php

namespace App\Models;


// 供应商退仓
use App\Notifications\WithdrawApprovedNotification;
use App\Notifications\WithdrawCompletedNotification;
use App\Notifications\WithdrawPendingNotification;
use App\Notifications\WithdrawShippedNotification;
use App\Notifications\WithdrawUnShipNotification;
use App\Services\WithdrawService;
use App\Traits\HasStatuses;
use App\Traits\TrackableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notification;

/**
 * @property mixed warehouse_id
 * @property mixed preInventoryAction
 * @property mixed origin
 * @property mixed description
 */
class Withdraw extends Model
{
    use HasStatuses, TrackableTrait;

    const UN_COMMIT = 'UN_COMMIT'; // 未提交(保存)
    const PENDING = 'PENDING';   // 待审核(提交审核)
    const APPROVED = 'APPROVED'; // 审核通过 等待仓库拣货
    const UN_SHIP = 'UN_SHIP';  // 待发货
    const PART_SHIPPED = 'PART_SHIPPED';  // 部分发货
    const SHIPPED = 'SHIPPED';  // 已发货
    const COMPLETED = 'COMPLETED'; // 已完成
    const CANCEL = 'CANCEL'; // 取消

    protected $fillable = [
        'description',
        'warehouse_id',
        'has_ship',
    ];

    protected $appendAuthorizes = ['submit', 'approve', 'completed'];

    protected $appends = ['current_state'];

    protected $touches = ['statuses'];

    protected $casts = [
        'has_ship' => 'bool',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            (new WithdrawService($model))->statusToSave();
        });
        // 如果供应商登录到系统，仅能看到自己的退货
        static::addGlobalScope('filterBySupplier', function (Builder $builder) {
            if (isSupplier()) {
                $supplier = auth()->user()->supplier;
                $builder->where([
                    ['origin_type', get_class($supplier)],
                    ['origin_id', $supplier->id],
                ]);
            }
        });
    }

    public function getCurrentStateAttribute()
    {
        $status = [
            self::UN_COMMIT => '未提交',
            self::PENDING => '已提交(审核中)',
            self::APPROVED => '审核通过,等待仓库拣货',
            self::UN_SHIP => '待发货',
            self::PART_SHIPPED => '部分发货',
            self::SHIPPED => '已发货',
            self::COMPLETED => '已完成',
            self::CANCEL => '已取消',
        ];
        return array_get($status, $this->state->name, 'N/A');
    }


    public function items()
    {
        return $this->hasMany(WithdrawItem::class);
    }

    // 预出\入库(入库单\出货单)
    public function preInventoryAction()
    {
        return $this->morphOne(PreInventoryAction::class, 'origin');
    }

    // 退货来源
    public function origin()
    {
        return $this->morphTo();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getSimpleAddressAttribute()
    {
        return $this->origin->simple_address;
    }

    /**
     * 运输方式 true=物流 false=无需物流
     * @return bool
     */
    public function transport()
    {
        return $this->has_ship ? true : false;
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

    public function freshNow()
    {
        $this->forceFill(['updated_at' => $this->freshTimestamp()])->save();
    }

    // 是否全部确认收货
    public function isAllChecked()
    {
        return $this->preInventoryAction->orders->map->items->flatten(1)->every->isAllChecked();
    }

    public function notify(Notification $notification)
    {
        $this->origin->admin->notify($notification);
    }

    // 审核通过通知
    public function approvedNotify()
    {
        $this->latestStatus(static::PENDING)->user->notify(new WithdrawApprovedNotification($this));
    }

    // 预出\入库(入库单\出货单)已生成 等待发货通知
    public function unShipNotify()
    {
        User::all()->filter(function ($user) {
            return $user->id !== auth()->user()->id;
        })->each->notify(new WithdrawUnShipNotification($this));
    }

    // 提交审核通知
    public function pendingNotify()
    {
        $this->origin->admin->notify(new WithdrawPendingNotification($this));
    }

    // 已发货通知
    public function shippedNotify()
    {
        $this->origin->users->each->notify(new WithdrawShippedNotification($this));

//        $this->preInventoryAction->orders->each(function ($order) {
//            // 发送通知给每个仓库
//            $order->warehouse->admin->notify(new SupplyShippedNotification($this));
//        });
    }

    // 完成通知
    public function completedNotify()
    {
        $this->origin->users->each->notify(new WithdrawCompletedNotification($this));
    }
}
