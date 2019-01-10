<?php

namespace App\Listeners;

use App\Models\Supply;
use App\Models\Withdraw;
use App\Services\SupplyService;
use App\Services\WithdrawService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InventoryEventSubscriber
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
        // 消息通知仓库调度中心工作人员
        $event->action->pendingNotify();
    }

    /*
     * 审核通过事件响应
     * */
    public function onApproved($event)
    {
        // 消息通知仓库分配工作人员
        $event->action->approvedNotify();

        // 生成操作单
    }

    /*
     * 操作单(拣货单\入仓单)分配完成 事件响应
     * */
    public function onAssigned($event)
    {
        // 消息通知供应商/仓库安排发货
        $origin = $event->action->origin;
        if ($origin instanceof Supply) {
            // 供货计划，标记为等待发货
            (new SupplyService($origin))->statusToUnShip();
        }
        if ($origin instanceof Withdraw) {
            (new WithdrawService($origin))->statusToUnShip();
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
            'App\Events\PreInventoryActionPendingEvent',
            'App\Listeners\InventoryEventSubscriber@onPending'
        );

        // 审核通过
        $events->listen(
            'App\Events\PreInventoryActionApprovedEvent',
            'App\Listeners\InventoryEventSubscriber@onApproved'
        );

        // 已分配出库 操作单(拣货单\入仓单) 已生成
        $events->listen(
            'App\Events\PreInventoryActionAssignedEvent',
            'App\Listeners\InventoryEventSubscriber@onAssigned'
        );
    }
}
