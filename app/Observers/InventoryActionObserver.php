<?php


namespace App\Observers;


use App\Models\Inventory;
use App\Models\InventoryAction;

class InventoryActionObserver
{
    public function created(InventoryAction $action)
    {
        // 更新库存
        Inventory::updateByAction($action);
    }
}