<?php

namespace App\Http\Controllers;

use App\Models\Logistic;
use App\Models\Order;
use App\Models\PreInventoryActionOrder;
use App\Models\Supply;
use App\Models\Withdraw;
use App\Services\OrderService;
use App\Services\SupplyService;
use App\Services\WithdrawService;
use Illuminate\Http\Request;

class PreInventoryActionOrderController extends Controller
{

    public static $resource = \App\Resources\PreInventoryActionOrder::class;

    /**
     * Display the specified resource.
     *
     * @param PreInventoryActionOrder $preInventoryActionOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PreInventoryActionOrder $preInventoryActionOrder)
    {
        $resource = $preInventoryActionOrder->loadDetailAttribute()->loadItemState();
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare();
        return view(static::$resource::uriKey() . '.detail', compact('resource'));
    }


    public function check(PreInventoryActionOrder $preInventoryActionOrder)
    {
        $resource = $preInventoryActionOrder->loadDetailAttribute()->loadItemState();
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare();
        return view(static::$resource::uriKey() . '.check', compact('resource'));
    }

    public function shipment(PreInventoryActionOrder $preInventoryActionOrder)
    {
        $preInventoryActionOrder->loadDetailAttribute()->loadType()->append('simple_address');
        $logistic = Logistic::all();
        $this->viewShare();
        return view(static::$resource::uriKey() . '.shipment',
            ['resource' => $preInventoryActionOrder, 'logistic' => $logistic]
        );
    }

    public function shipped(PreInventoryActionOrder $preInventoryActionOrder, Request $request)
    {
        $origin = $preInventoryActionOrder->preInventoryAction->origin;
        if ($origin instanceof Order) {
            // 订单出库
            (new OrderService($origin))->shipment($preInventoryActionOrder, $request);
        }

        if ($origin instanceof Supply) {
            // 供应商入库
            (new SupplyService($origin))->shipment($preInventoryActionOrder, $request);
        }

        if ($origin instanceof Withdraw) {
            // 供应商退仓
            (new WithdrawService($origin))->shipment($preInventoryActionOrder, $request);
        }

        $preInventoryActionOrder->loadDetailAttribute()->loadType()->append('simple_address');

        return $this->updated(
            $preInventoryActionOrder,
            '发货成功'
        );
    }
}
