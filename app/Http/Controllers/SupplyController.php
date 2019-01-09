<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErpRequest;
use App\Http\Requests\SupplyRequest;
use App\Models\Logistic;
use App\Models\PreInventoryActionOrder;
use App\Models\Supply;
use App\Services\SupplyService;
use Illuminate\Http\Request;

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
        return view(static::$resource::uriKey() . '.create');
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
            return redirect()->route(static::$resource::uriKey() . '.show', [ 'supply' => $supply->id ]);
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
        $resource = $supply->loadMissing([ 'origin', 'items.variant', 'statuses.user' ]);
        if (request()->ajax()) {
            return response()->json($resource);
        } else {
            $this->viewShare([ 'resourceId' => $supply->id ]);
            return view(static::$resource::uriKey() . '.detail', compact('resource'));
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
//        dd($resource);
        if (request()->ajax()) {
            return response()->json($resource);
        } else {
            return view(static::$resource::uriKey() . '.update', compact('resource'));
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


    public function approved(Supply $supply, ErpRequest $request)
    {
        (new SupplyService($supply))->statusToApproved();
        if (request()->ajax()) {
            return $this->updated([], '审核完成');
        } else {
            return redirect()->route($request->getSubDomain() . '.supplies.show', [ 'supply' => $supply->id ]);
        }
    }

    public function submit(Supply $supply, ErpRequest $request)
    {
        (new SupplyService($supply))->statusToPending();
        if (request()->ajax()) {
            return $this->updated([], '供货计划已提交', '您的提交会尽快处理,请耐心等待');
        } else {
            return redirect()->route($request->getSubDomain() . '.supplies.show', [ 'supply' => $supply->id ]);
        }
    }

    /**
     * 发货页面
     * @param Supply $supply
     * @param PreInventoryActionOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function shipment(Supply $supply, PreInventoryActionOrder $order)
    {
        $order->loadDetailAttribute();
        $order->warehouse->append('simple_address');
        $logistic = Logistic::all();
        return view(static::$resource::uriKey() . '.shipment', [ 'resource' => $supply, 'order' => $order, 'logistic' => $logistic ]);
    }

    /**
     * 发货请求
     * @param Supply $supply
     * @param PreInventoryActionOrder $order
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    public function completed(Supply $supply, SupplyRequest $request)
    {
        (new SupplyService($supply))->statusToCompleted();
        if ($request->ajax()) {
            return $this->updated(
                [],
                '入库计划已确认'
            );
        }
        return redirect()->route($request->getSubDomain() . '.supplies.show', [ 'supply' => $supply->id ]);

    }

}
