<?php

namespace App\Http\Controllers;

use App\Resources\WarehouseType;
use Illuminate\Http\Request;

class WarehouseTypeController extends Controller
{
    public static $resource = WarehouseType::class;

    public function create()
    {
        $this->viewShare();
        return view(static::$resource::uriKey() . '.create');
    }

    public function store(Request $request)
    {
        return $this->created(
            \App\Models\WarehouseType::create($request->all())
        );
    }

    public function show(\App\Models\WarehouseType $warehouseType)
    {
        $resource = $warehouseType;
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare();
        return view(static::$resource::uriKey() . '.detail', compact('resource'));
    }

    public function edit(\App\Models\WarehouseType $warehouseType)
    {
        $resource = $warehouseType;
        $this->viewShare();
        return view(static::$resource::uriKey() . '.update', compact('resource'));
    }

    public function update(Request $request, \App\Models\WarehouseType $warehouseType)
    {
        $warehouseType->fill($request->all());
        $warehouseType->save();
        return $this->updated(
            $warehouseType
        );
    }
}
