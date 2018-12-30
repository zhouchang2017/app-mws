<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/12/30
 * Time: 下午10:10
 */

namespace App\Observers;


use App\Models\PreInventoryActionOrder;
use App\Notifications\newPreInventoryActionOrderNotification;

class PreInventoryActionOrderObserver
{
    public function created(PreInventoryActionOrder $order)
    {
        $order->warehouse->admin->notify(new newPreInventoryActionOrderNotification($order));
    }
}