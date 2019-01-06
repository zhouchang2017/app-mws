<?php

namespace App\Listeners;

use App\Services\SupplyService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupplyEventSubscriber
{

    /*
     * 待审核事件响应
     * */
    public function onPending($event)
    {
        // 消息通知给供官方小二
        $event->supply->pendingNotify();
    }

    /*
     * 审核通过事件响应
     * */
    public function onApproved($event)
    {
        // 推送供货计划到 【库存调配系统】 生成 预出\入库(入库单\出货单)
        (new SupplyService($event->supply))->createPreAction();
        // 消息通知给供应商
        $event->supply->approvedNotify();
    }

    /*
     * 已分配接收仓库，待发货事件响应
     * */
    public function onUnShip($event)
    {
        // 消息通知给供应商
        $event->supply->unShipNotify();
    }

    /*
     * 发货完成
     * */
    public function onShipped($event)
    {
        // 通知平台
        $event->supply->shippedNotify();
    }

    /*
     * 确认完成
     * */
    public function onCompleted($event)
    {
        // 通知供应商
        $event->supply->completedNotify();
    }


    /**
     * 为订阅者注册监听器。
     *
     * @param  \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\SupplyPendingEvent',
            'App\Listeners\SupplyEventSubscriber@onPending'
        );

        $events->listen(
            'App\Events\SupplyApprovedEvent',
            'App\Listeners\SupplyEventSubscriber@onApproved'
        );

        $events->listen(
            'App\Events\SupplyUnShipEvent',
            'App\Listeners\SupplyEventSubscriber@onUnShip'
        );

        $events->listen(
            'App\Events\SupplyShippedEvent',
            'App\Listeners\SupplyEventSubscriber@onShipped'
        );

        $events->listen(
            'App\Events\SupplyCompletedEvent',
            'App\Listeners\SupplyEventSubscriber@onCompleted'
        );
    }
}
