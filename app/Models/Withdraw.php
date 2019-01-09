<?php

namespace App\Models;


// 供应商退仓
use App\Traits\HasStatuses;
use App\Traits\TrackableTrait;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property mixed warehouse_id
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

    protected $appends = ['current_state'];

    protected $touches = ['statuses'];

    protected $casts = [
        'has_ship' => 'bool',
    ];

    protected static function boot()
    {
        parent::boot();
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

    /**
     * 运输方式 true=物流 false=无需物流
     * @return bool
     */
    public function transport()
    {
        return $this->has_ship ? true : false;
    }

    public function freshNow()
    {
        $this->forceFill(['updated_at' => $this->freshTimestamp()])->save();
    }
}
