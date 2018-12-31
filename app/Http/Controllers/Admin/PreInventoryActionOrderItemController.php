<?php

namespace App\Http\Controllers\Admin;

use App\Models\PreInventoryActionOrderItem;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreInventoryActionOrderItemController extends Controller
{
    public function addCheck(PreInventoryActionOrderItem $preInventoryActionOrderItem, Request $request)
    {
        return $this->updated(
            InventoryService::preActionOrderItemCheck($preInventoryActionOrderItem, $request),
            '操作完成'
        );
    }
}
