<?php


namespace App\Observers;


use App\Models\DP\ProductVariant;
use App\Models\Inventory;
use App\Models\InventoryAction;

class InventoryActionObserver
{
    public function created(InventoryAction $action)
    {
        // 更新库存
        Inventory::updateByAction($action);
        // 更新产品冗余库存
        $action->type->isTake() ?
            $action->variant()->decrement('stock', $action->quantity) :
            $action->variant()->increment('stock', $action->quantity);
    }
}