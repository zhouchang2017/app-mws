<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/7
 * Time: 3:27 PM
 */

namespace App\Listeners;

use App\Notifications\ProductApprovedNotification;
use App\Notifications\ProductPendingNotification;
use App\Notifications\ProductRejectedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductEventSubscriber
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

    /*
     * 待审核事件响应
     * */
    public function onPending($event)
    {
        // 通知对接小二
        if ($supplier = $event->product->suppliers()->first()) {
            $supplier->admin->notify(new ProductPendingNotification($event->product));
        }
    }

    /*
     * 审核通过事件响应
     * */
    public function onApproved($event)
    {
        // 通知供应商
        if ($supplier = $event->product->suppliers()->first()) {
            $supplier->users->each->notify(new ProductApprovedNotification($event->product));
        }
    }

    /*
     * 审核拒绝事件响应
     * */
    public function onRejected($event)
    {
        // 通知供应商
        if ($supplier = $event->product->suppliers()->first()) {
            $supplier->users->each->notify(new ProductRejectedNotification($event->product));
        }
    }

    /**
     * 为订阅者注册监听器。
     *
     * @param  \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        // 待审核
        $events->listen(
            'App\Events\ProductPendingEvent',
            'App\Listeners\ProductEventSubscriber@onPending'
        );

        // 审核通过
        $events->listen(
            'App\Events\ProductApprovedEvent',
            'App\Listeners\ProductEventSubscriber@onApproved'
        );

        // 审核拒绝
        $events->listen(
            'App\Events\ProductRejectedEvent',
            'App\Listeners\ProductEventSubscriber@onRejected'
        );
    }
}