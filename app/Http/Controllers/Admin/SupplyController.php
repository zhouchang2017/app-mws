<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ErpRequest;
use App\Http\Requests\SupplyRequest;
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
            return redirect(route('supplier.supplies.show', ['supply' => $supply->id]));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Supply $supply
     * @return \Illuminate\Http\Response
     */
    public function show(Supply $supply,ErpRequest $request)
    {

        $resource = $supply->loadMissing(['origin', 'items.variant','statuses.user']);
        if (request()->ajax()) {
            return response()->json($resource);
        } else {
            $this->viewShare(['resourceId'=>$supply->id]);
            return view('supplies.detail', compact('resource'));
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
        $resource = $supply->loadMissing(['origin', 'items.variant']);
//        dd($resource);
        if (request()->ajax()) {
            return response()->json($resource);
        } else {
            return view('admin.pages.supplies.update', compact('resource'));
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


    public function approved(Supply $supply)
    {
        (new SupplyService($supply))->statusToApproved();
        if (request()->ajax()) {
            return $this->updated([], '审核完成');
        } else {
            return redirect()->route('admin.supplies.show', ['supply' => $supply->id]);
        }
    }

}
