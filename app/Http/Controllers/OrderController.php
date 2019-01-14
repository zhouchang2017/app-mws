<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Resources\Order as OrderResource;

class OrderController extends Controller
{
    public static $resource = OrderResource::class;

    public function show(Order $order)
    {
        $resource = $order->loadItemsWithMarketPrice();

        $resource->append(['simple_address']);

        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare();
        return view(static::$resource::uriKey() . '.detail', compact('resource'));
    }

    public function createPreInventoryAction(Order $order)
    {
        (new OrderService($order))->createPreInventoryAction();
        return redirect()->route('admin.' . static::$resource::uriKey() . '.show', ['order' => $order]);
    }
}
