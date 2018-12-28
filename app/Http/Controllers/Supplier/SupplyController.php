<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Requests\SupplyRequest;
use App\Models\DP\ProductVariant;
use App\Models\Supply;
use App\Services\SupplyService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Supply::paginate(15);
        if (request()->ajax()) {
            return $resources;
        }
        return view('supplier.pages.supplies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $variants = ProductVariant::paginate(15)->toArray();
        return view('supplier.pages.supplies.create', compact('variants'));
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
            dd($supply);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Supply $supply)
    {
        $resource = $supply->with([ 'origin', 'items.variant' ])->first();
        if (request()->ajax()) {
            return $resource;
        } else {
            return view('supplier.pages.supplies.detail', compact('resource'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Supply $supply)
    {
        $resource = $supply->with([ 'origin', 'items.variant' ])->first();
        if (request()->ajax()) {
            return $resource;
        } else {
            return view('supplier.pages.supplies.update', compact('resource'));
        }
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
}
