<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/27
 * Time: 9:55 AM
 */

namespace App\Services;


use App\Models\PreInventoryAction;
use App\Models\PreInventoryActionOrder;

class InventoryService
{
    /*
     * 创建 预出\入库(入库单\出货单)
     * */
    public static function createPreAction($data, $origin = null)
    {
        return tap(new PreInventoryAction($data), function ($preAction) use ($origin) {
            /** @var PreInventoryAction $preAction */
            $preAction->origin()->associate($origin)->save();
        });
    }

    public static function createPreActionOrder(PreInventoryAction $action, $data)
    {
        return collect($data)->groupBy('warehouse_id')->map(function ($items, $key) use ($action) {
            /** @var PreInventoryActionOrder $order */
            $order = $action->orders()->create([
                'warehouse_id' => $key,
                'type_id' => $action->type->id,
            ]);
            $items->each(function ($item) use ($order) {
                (new static)->createPreActionOrderItem($order, $item);
            });
            return $order;
        })->flatten(1)->tap(function () use ($action) {
            // 操作单(拣货单\入仓单) 创建完成
            $action->statusToAssigned();
        });
    }



    protected function createPreActionOrderItem(PreInventoryActionOrder $order, $data)
    {
        return $order->items()->create($data);
    }
}