<?php

namespace App\Models\DP;


use App\Contracts\Orderable;
use App\Models\DP\Enums\OrderState;
use App\Services\OrderService;
use App\Supports\ExpendItems;
use App\Models\Order as BaseOrder;
use App\Traits\MoneyFormatableTrait;

/**
 * @property mixed state
 * @property mixed shipping_state
 * @property mixed payment_state
 * @property mixed total
 */
class Order extends Model implements Orderable
{
    use MoneyFormatableTrait;

    public $priceFields = ['items_total', 'adjustments_total', 'total'];

    protected $casts = [
        'rest' => 'array',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'fulfilled_at' => 'datetime',
    ];

    public function scopeCheckout($query)
    {
        return $query->where('state', OrderState::CHECKOUT);
    }

    public function scopeOfNumber($query, $number)
    {
        return $query->where('number', $number);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function units()
    {
        return $this->hasManyThrough(
            OrderItemUnit::class,
            OrderItem::class,
            'order_id',
            'item_id'
        );
    }

    public function itemsAdjustments()
    {
        return $this->hasManyThrough(
            Adjustment::class,
            OrderItem::class
        );
    }

    public function adjustments()
    {
        return $this->hasMany(Adjustment::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_order');
    }

    public function getAdjustmentsTotal(String $type = null): int
    {
        // TODO 待递归查找
        if ($type === null) {
            return $this->adjustments_total;
        }

        $total = 0;
        foreach ($this->adjustments()->ofType($type)->get() as $adjustment) {
            if ( !$adjustment->is_neutral) {
                $total += $adjustment->amount;
            }
        }

        return $total;
    }

    // 订单明细
    public function getExpendItems()
    {
        $data = $this
            ->items()
            ->get(['product_id', 'variant_id', 'quantity', 'units_total'])
            ->map(function ($order) {
                return [
                    'product_id' => $order->product_id,
                    'product_variant_id' => $order->variant_id,
                    'pcs' => $order->quantity,
                    'price' => $order->units_total,
                ];
            });
        return new ExpendItems($data);
    }

    // 订单描述
    public function getDescriptionAttribute()
    {
        return $this->channel->name . '订单(' . $this->number . ')';
    }

    // 统一订单状态
    public function getStatusAttribute()
    {
        // 订单取消
        if ($this->state === 'cancelled') {
            return BaseOrder::CANCEL;
        }
        // 订单已发货
        if ($this->shipping_state === 'shipped') {
            return BaseOrder::SHIPPED;
        }
        // 订单以付款
        if ($this->payment_state === 'paid') {
            return BaseOrder::UN_SHIP;
        }
        // 等待买家付款
        if ($this->state === 'new') {
            return BaseOrder::PENDING;
        }

        return BaseOrder::PENDING;
    }

    public function getTotalPriceAttribute()
    {
        return $this->total;
    }

    public function getMarketIdAttribute()
    {
        return optional($this->channel->market)->id;
    }

    public function sync()
    {
        return OrderService::syncOrder($this);
    }

}
