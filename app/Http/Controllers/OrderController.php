<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Resources\Order as OrderResource;

class OrderController extends Controller
{
    public static $resource = OrderResource::class;

    public function show(Order $order)
    {
        $resource = $order->loadMissing(['items','market']);
        $resource->append(['simple_address']);
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare();
        return view(static::$resource::uriKey() . '.detail', compact('resource'));
    }
}
