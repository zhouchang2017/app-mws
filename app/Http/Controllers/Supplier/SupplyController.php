<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Requests\SupplyRequest;
use App\Models\DP\ProductVariant;
use App\Models\Logistic;
use App\Models\PreInventoryActionOrder;
use App\Models\Supply;
use App\Services\SupplyService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplyController extends Controller
{
    public static $resource = \App\Resources\Supply::class;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supplier.pages.supplies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SupplyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplyRequest $request)
    {
        $supply = SupplyService::createSupply($request);
        if ($request->ajax()) {
            return $this->created($supply);
        } else {
            return redirect(route('supplier.supplies.show', [ 'supply' => $supply->id ]));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Supply $supply
     * @return \Illuminate\Http\Response
     */
    public function show(Supply $supply)
    {
        $resource = $supply->loadMissing([ 'origin', 'items.variant' ]);
        if (request()->ajax()) {
            return response()->json($resource);
        } else {
            return view('supplier.pages.supplies.detail', compact('resource'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Supply $supply
     * @return \Illuminate\Http\Response
     */
    public function edit(Supply $supply)
    {
        $resource = $supply->loadMissing([ 'origin', 'items.variant' ]);
        if (request()->ajax()) {
            return response()->json($resource);
        } else {
            return view('supplier.pages.supplies.update', compact('resource'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SupplyRequest $request
     * @param Supply $supply
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SupplyRequest $request, Supply $supply)
    {
        $res = (new SupplyService($supply))->updateSupply($request);
        return $this->updated($res);
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

    public function submit(Supply $supply)
    {
        (new SupplyService($supply))->statusToPending();
        if (request()->ajax()) {
            return $this->updated([], '供货计划已提交', '您的提交会尽快处理,请耐心等待');
        } else {
            return redirect()->route('supplier.supplies.show', [ 'supply' => $supply->id ]);
        }
    }

    public function shipment(Supply $supply, PreInventoryActionOrder $order)
    {
        $order->loadDetailAttribute();
        $order->warehouse->append('simple_address');
        $logistic = Logistic::all();
        return view('supplier.pages.supplies.shipment', [ 'resource' => $supply, 'order' => $order, 'logistic' => $logistic ]);
    }

    public function shipped(Supply $supply, PreInventoryActionOrder $order, Request $request)
    {
        (new SupplyService($supply))->shipment($order, $request);
        $supply->refresh();
        $order->refresh();
        $order->loadDetailAttribute();
        $order->warehouse->append('simple_address');
        return $this->updated(
            [ 'resource' => $supply, 'order' => $order ],
            '发货成功'
        );
    }
}
