<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErpRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Resources\Supplier as SupplierResource;

class SupplierController extends Controller
{
    public static $resource = SupplierResource::class;

    public function create()
    {
        $this->viewShare();
        return view(static::$resource::uriKey() . '.create');
    }

    public function store(Request $request)
    {
        $supplier = Supplier::create($request->all());

        return $this->created(
            $supplier
        );
    }

    public function show(Supplier $supplier)
    {
        $resource = $supplier->loadMissing([ 'manager', 'admin', 'users' ]);
        if (request()->ajax()) {
            return response()->json($resource);
        } else {
            $this->viewShare();
            return view(static::$resource::uriKey() . '.detail', compact('resource'));
        }
    }

    public function storeAddress(Supplier $supplier, ErpRequest $request)
    {

    }
}
