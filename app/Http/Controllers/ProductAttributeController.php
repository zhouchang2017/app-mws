<?php

namespace App\Http\Controllers;

use App\Models\DP\ProductAttribute;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{

    public static $resource = \App\Resources\ProductAttribute::class;


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
            ProductService::updateOrCreateProductAttribute($request)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param ProductAttribute $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function show(ProductAttribute $productAttribute)
    {
        $resource = $productAttribute->loadMissing([ 'taxon' ]);
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare();
        return view(static::$resource::uriKey() . '.detail', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductAttribute $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductAttribute $productAttribute)
    {
        $resource = $productAttribute->loadMissing([ 'taxon' ]);
        $resource->taxon->append('ancestors');
        $this->viewShare();
        return view(static::$resource::uriKey() . '.update', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param ProductAttribute $productAttribute
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ProductAttribute $productAttribute)
    {
        return $this->updated(
            ProductService::updateOrCreateProductAttribute($request, $productAttribute)
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
