<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErpRequest;
use App\Models\SupplierUser;
use Illuminate\Http\Request;
use App\Resources\SupplierUser as SupplierUserResource;
use Illuminate\Support\Facades\Hash;

class SupplierUserController extends Controller
{
    public static $resource = SupplierUserResource::class;

    public function create()
    {
        $this->viewShare();
        return view(static::$resource::uriKey() . '.create');
    }

    public function store(Request $request)
    {
        return $this->created(
            tap((new SupplierUser())->forceFill([
                'name'        => $request->get('name'),
                'email'       => $request->get('email'),
                'phone'       => $request->get('phone'),
                'supplier_id' => $request->get('supplier_id', null),
                'password'    => Hash::make($request->get('password')),
            ]))->save()
        );
    }

    public function show(SupplierUser $supplierUser)
    {
        $resource = $supplierUser->loadMissing([ 'supplier' ]);
        if (request()->ajax()) {
            return response()->json($resource);
        } else {
            $this->viewShare();
            return view(static::$resource::uriKey() . '.detail', compact('resource'));
        }
    }

    public function edit(SupplierUser $supplierUser)
    {
        $resource = $supplierUser->loadMissing([ 'supplier' ]);
        $this->viewShare();
        return view(static::$resource::uriKey() . '.update', compact('resource'));
    }

    public function update(SupplierUser $supplierUser, ErpRequest $request)
    {
        return $this->updated(
            $supplierUser->forceFill([
                'name'        => $request->get('name'),
                'email'       => $request->get('email'),
                'phone'       => $request->get('phone'),
                'supplier_id' => $request->get('supplier_id', null),
                'password'    => Hash::make($request->get('password')),
            ])->save()
        );
    }
}
