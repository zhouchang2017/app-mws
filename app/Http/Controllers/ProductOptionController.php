<?php

namespace App\Http\Controllers;

use App\Models\DP\ProductOption;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductOptionController extends Controller
{

    public static $resource = \App\Resources\ProductOption::class;

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
            ProductService::updateOrCreateProductOption($request)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param ProductOption $productOption
     * @return \Illuminate\Http\Response
     */
    public function show(ProductOption $productOption)
    {
        $resource = $productOption->loadMissing([ 'taxon' ]);
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare();
        return view(static::$resource::uriKey() . '.detail', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductOption $productOption
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductOption $productOption)
    {
        $resource = $productOption->loadMissing([ 'taxon' ]);
        $resource->taxon->append('ancestors');
        $this->viewShare();
        return view(static::$resource::uriKey() . '.update', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param ProductOption $productOption
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ProductOption $productOption)
    {
        return $this->updated(
            ProductService::updateOrCreateProductOption($request, $productOption)
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
