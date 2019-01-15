<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/7
 * Time: 3:27 PM
 */

namespace App\Listeners;

use App\Exceptions\OrderBillHasAlreadyExistsException;
use App\Models\Bill;
use App\Models\User;
use App\Notifications\CompletedOrderNotification;
use App\Notifications\NewOrderNotification;
use App\Notifications\PaymentOrderNotification;
use App\Notifications\ProductApprovedNotification;
use App\Notifications\ProductPendingNotification;
use App\Notifications\ProductRejectedNotification;
use App\Services\BillService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderEventSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param $event
     * @throws \Throwable
     */
    public function onNew($event)
    {
        // 创建账单
        throw_unless($event->order->bills()->count() === 0, OrderBillHasAlreadyExistsException::class);
        $event->order->items->each(function ($item, $index) use ($event) {
            tap(new Bill())->fill([
                'number' => $item->variant->supplier->first()->code . date('YmdHis') . $index,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'supplier_id' => $item->variant->supplier->first()->id,
                'quantity' => $item->quantity,
                'origin_price' => $item->origin_price,
                'price' => $item->price,
                'rest' => $item->rest,
            ])->origin()->associate($event->order)->save();
        });

        User::all()->each->notify(new NewOrderNotification($event->order));
    }


    public function onPayment($event)
    {
        // 通知小二
        User::all()->each->notify(new PaymentOrderNotification($event->order));
    }

    public function onShipped($event)
    {
        // 订单已发货
    }

    public function onCompleted($event)
    {
        // 交易完成
        User::all()->each->notify(new CompletedOrderNotification($event->order));
        // 账单生效
        $event->order->bills->each(function ($bill) {
            // 所有账单失效
            (new BillService($bill))->statusToActive('订单交易完成，账单生效');
        });
    }

    public function onCancel($event)
    {
        // 订单取消
        $event->order->bills->each(function ($bill) {
            // 所有账单失效
            (new BillService($bill))->statusToCancel();
        });
    }


    /**
     * 为订阅者注册监听器。
     *
     * @param  \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        // DP订单同步
        $events->listen(
            'App\Events\NewOrderEvent',
            'App\Listeners\OrderEventSubscriber@onNew'
        );

        // 订单已付款
        $events->listen(
            'App\Events\PaymentOrderEvent',
            'App\Listeners\OrderEventSubscriber@onPayment'
        );

        // 订单已发货
        $events->listen(
            'App\Events\ShippedOrderEvent',
            'App\Listeners\OrderEventSubscriber@onShipped'
        );

        // 交易完成
        $events->listen(
            'App\Events\CompletedOrderEvent',
            'App\Listeners\OrderEventSubscriber@onCompleted'
        );

        // 订单取消
        $events->listen(
            'App\Events\CancelOrderEvent',
            'App\Listeners\OrderEventSubscriber@onCancel'
        );
    }
}