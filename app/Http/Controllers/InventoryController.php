<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    public static $resource = \App\Resources\Inventory::class;

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
     * @param Inventory $inventory
     * @return \Illuminate\Http\Response
     */
    public function show(Inventory $inventory)
    {
        $resource = $inventory->loadMissing(['warehouse', 'variant']);
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare();
        return view(static::$resource::uriKey() . '.detail', compact('resource'));
    }

    public function histories(Inventory $inventory)
    {
        return response()->json(
            $inventory->histories()->with(['type', 'origin'])
        );
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

    public function search()
    {
        // inventories/search?variant_id=1&warehouse_id=1
        return response()->json(
            Inventory::with('warehouse')->search()->latest('quantity')->get()
        );
    }
}
