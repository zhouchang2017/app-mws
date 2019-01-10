<?php

namespace App\Listeners;

use App\Services\SupplyService;
use App\Services\WithdrawService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WithdrawEventSubscriber
{

    /*
     * 待审核事件响应
     * */
    public function onPending($event)
    {
        // 消息通知给供官方小二
        $event->withdraw->pendingNotify();
    }

    /*
     * 审核通过事件响应
     * */
    public function onApproved($event)
    {
        // 推送供货计划到 【库存调配系统】 生成 预出\入库(入库单\出货单)
        (new WithdrawService($event->withdraw))->createPreAction();
        // 消息通知给供应商
        $event->withdraw->approvedNotify();
    }

    /*
     * 已分配接收仓库，待发货事件响应
     * */
    public function onUnShip($event)
    {
        // 消息通知给供应商
        $event->withdraw->unShipNotify();
    }

    /*
     * 发货完成
     * */
    public function onShipped($event)
    {
        // 通知平台
        $event->withdraw->shippedNotify();
    }

    /*
     * 确认完成
     * */
    public function onCompleted($event)
    {
        // 通知供应商
        $event->withdraw->completedNotify();
    }


    /**
     * 为订阅者注册监听器。
     *
     * @param  \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\WithdrawPendingEvent',
            'App\Listeners\WithdrawEventSubscriber@onPending'
        );

        $events->listen(
            'App\Events\WithdrawApprovedEvent',
            'App\Listeners\WithdrawEventSubscriber@onApproved'
        );

        $events->listen(
            'App\Events\WithdrawUnShipEvent',
            'App\Listeners\WithdrawEventSubscriber@onUnShip'
        );

        $events->listen(
            'App\Events\WithdrawShippedEvent',
            'App\Listeners\WithdrawEventSubscriber@onShipped'
        );

        $events->listen(
            'App\Events\WithdrawCompletedEvent',
            'App\Listeners\WithdrawEventSubscriber@onCompleted'
        );
    }
}
