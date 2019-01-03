<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/26
 * Time: 1:44 PM
 */

namespace App\Observers;


use App\Models\InventoryAction;
use App\Models\PreInventoryActionOrderItemState;

class PreInventoryActionOrderItemStateObserver
{
    public function created(PreInventoryActionOrderItemState $actionOrderItemState)
    {
        // 生成 库存操作记录
        // create inventory-action
        $action = new InventoryAction([
            'product_id' => $actionOrderItemState->product_id,
            'variant_id' => $actionOrderItemState->variant_id,
            'quantity' => $actionOrderItemState->quantity,
            'warehouse_id' => $actionOrderItemState->warehouse->id,
            'warehouse_area' => $actionOrderItemState->warehouse_area,
            'type_id' => $actionOrderItemState->type_id,
            'action_type_name' => $actionOrderItemState->type->name,
        ]);

        $action->origin()->associate($actionOrderItemState->origin)->save();
    }
}