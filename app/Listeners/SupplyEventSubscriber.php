<?php

namespace App\Listeners;

use App\Notifications\SupplyPendingNotification;
use App\Notifications\SupplyUnShipNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupplyEventSubscriber
{

    /*
     * 待审核通知
     * */
    public function onPending($event)
    {
        $event->supply->notify(new SupplyPendingNotification($event->supply));
    }

    public function onUnShip($event)
    {
        $event->supply->notify(new SupplyUnShipNotification($event->supply));
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
            'App\Events\SupplyUnShipEvent',
            'App\Listeners\SupplyEventSubscriber@onUnShip'
        );

    }
}
