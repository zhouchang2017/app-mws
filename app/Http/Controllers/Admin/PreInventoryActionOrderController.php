<?php

namespace App\Http\Controllers\Admin;

use App\Models\Logistic;
use App\Models\Order;
use App\Models\PreInventoryActionOrder;
use App\Models\Supply;
use App\Services\OrderService;
use App\Services\SupplyService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreInventoryActionOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = PreInventoryActionOrder::with(['warehouse', 'type'])
            ->latest('updated_at')
            ->paginate(15);
        if (request()->ajax()) {
            return $resources;
        }
        return view('admin.pages.pre-inventory-action-orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param PreInventoryActionOrder $preInventoryActionOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PreInventoryActionOrder $preInventoryActionOrder)
    {
        $resource = $preInventoryActionOrder->loadDetailAttribute();
        if (request()->ajax()) {
            return response()->json($resource);
        }
        return view('admin.pages.pre-inventory-action-orders.detail', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function check(PreInventoryActionOrder $preInventoryActionOrder)
    {
        $resource = $preInventoryActionOrder->loadDetailAttribute()->loadItemState();
        if (request()->ajax()) {
            return response()->json($resource);
        }
        return view('admin.pages.pre-inventory-action-orders.check', compact('resource'));
    }

    public function shipment(PreInventoryActionOrder $preInventoryActionOrder)
    {
        $preInventoryActionOrder->loadDetailAttribute()->loadType()->append('simple_address');
        $logistic = Logistic::all();
        return view('admin.pages.pre-inventory-action-orders.shipment',
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

        $preInventoryActionOrder->loadDetailAttribute()->loadType()->append('simple_address');

        return $this->updated(
            ['resource' => $preInventoryActionOrder],
            '发货成功'
        );
    }
}
