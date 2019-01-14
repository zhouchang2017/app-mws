<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErpRequest;
use App\Models\Address;
use App\Models\Supplier;
use App\Services\AddressService;
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
        $resource = $supplier->loadMissing(['manager', 'admin', 'users']);
        if (request()->ajax()) {
            return response()->json($resource);
        } else {
            $this->viewShare();
            return view(static::$resource::uriKey() . '.detail', compact('resource'));
        }
    }

    public function profile()
    {
        if (erpRequest()->isSupplier()) {
            $supplier = auth()->user()->supplier;
            $resource = $supplier->loadMissing(['manager', 'admin', 'users']);
            $this->viewShare();
            return view(static::$resource::uriKey() . '.profile', compact('resource'));
        }
        abort(404);
    }

    public function address(Supplier $supplier, ErpRequest $request)
    {
        if ($request->method() === 'POST') {
            return $this->created(
                AddressService::updateOrCreateAddress($supplier, $request->all())
            );
        }
        if ($request->method() === 'PATCH') {
            $id = $request->get('id');
            $address = Address::find($id);
            return $this->updated(

                AddressService::updateOrCreateAddress($supplier, $request->all(), $address)
            );
        }

    }
}
