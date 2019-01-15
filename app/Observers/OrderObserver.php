<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/15
 * Time: 10:20 AM
 */

namespace App\Observers;

use App\Models\Bill;
use App\Models\DP\Channel;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;

class OrderObserver
{
    public function created(Order $order)
    {
//        $service = new OrderService($order);
//        $service->createOrderItems()
//            ->each(function ($item, $index) use ($order) {
//                tap(new Bill())->fill([
//                    'number' => $item->variant->supplier->first()->code . date('YmdHis') . $index,
//                    'product_id' => $item->product_id,
//                    'variant_id' => $item->variant_id,
//                    'supplier_id' => $item->variant->supplier->first()->id,
//                    'quantity' => $item->quantity,
//                    'origin_price' => $this->getOriginPrice($order, $item),
//                    'price' => $item->price,
//                ])->origin()->associate($order)->save();
//            });
    }

    public function getOriginPrice(Order $order, OrderItem $item)
    {
        return $item->variant->resolveCurrentPrice($order->market->marketable, $item);
    }
}