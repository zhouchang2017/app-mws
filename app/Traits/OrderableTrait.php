<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/11/1
 * Time: 下午11:40
 */

namespace Chang\Erp\Traits;


use Chang\Erp\Contracts\Orderable;
use Chang\Erp\Events\NewOrderEvent;
use Chang\Erp\Models\Order;

trait OrderableTrait
{
    public function order()
    {
        return $this->morphOne(Order::class, 'orderable');
    }

    public function register()
    {
        return [
            'order_status' => $this->getStatus(),
            'market_id' => $this->getMarketId(),
            'price' => (string)$this->getTotalPrice(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
        ];
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public static function syncOrder($orderId)
    {
        return static::findOrFail($orderId)->sync();
    }

    public function syncFilter()
    {
        return true;
    }

    public function sync()
    {
        if ($this->syncFilter()) {
            if (is_null($this->order)) {
                // 创建
                return $this->order()->create($this->register());
            }
            // 更新
            return tap($this->order, function ($order) {
                $order->update($this->register());
            });
        }
        throw new \Exception('订单状态不符合规则');
    }

    public static function syncAll()
    {
        return static::all()->map(function (Orderable $orderable) {
            return $orderable->sync();
        });
    }

}