<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Resources\Warehouse as WarehouseResource;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{

    public static $resource = WarehouseResource::class;



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->viewShare();
        return view(static::$resource::uriKey() . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->created(
            Warehouse::create($request->all())
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Warehouse $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse)
    {
        $resource = $warehouse->loadMissing(['type','admin']);
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare();
        return view(static::$resource::uriKey() . '.detail', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Warehouse $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        $resource = $warehouse->loadMissing(['type','admin']);
        $this->viewShare();
        return view(static::$resource::uriKey() . '.update', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Warehouse $warehouse
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $warehouse->fill($request->all());
        $warehouse->save();
        return $this->updated(
            $warehouse
        );
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
