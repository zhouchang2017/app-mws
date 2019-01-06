<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErpRequest;
use App\Models\DP\Product;
use App\Models\DP\ProductVariant;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{

    public static $resource = \App\Resources\ProductVariant::class;


    /**
     * Show the form for creating a new resource.
     *
     * @param ErpRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(ErpRequest $request)
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
        $service = new ProductService(Product::find($request->get('product_id')));
        return $this->created(
            $service->updateOrCreateProductVariant($request)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param ProductVariant $productVariant
     * @return \Illuminate\Http\Response
     */
    public function show(ProductVariant $productVariant)
    {
        $resource = $productVariant->loadMissing([ 'product', 'optionValues.option', 'supplier', 'inventories', 'price' ]);
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare();
        return view(static::$resource::uriKey() . '.detail', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductVariant $productVariant
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductVariant $productVariant)
    {
        $resource = $productVariant->loadMissing([ 'product', 'optionValues.option', 'supplier', 'price' ]);
        $this->viewShare([ 'viaRelationName' => 'product', 'viaRelationId' => $resource->product->id ]);
        return view(static::$resource::uriKey() . '.update', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param ProductVariant $productVariant
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ProductVariant $productVariant)
    {
        $service = new ProductService(Product::find($request->get('product_id')));
        return $this->created(
            $service->updateOrCreateProductVariant($request, $productVariant)
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

    /**
     * 检测code唯一
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCode($code)
    {
        return response()->json(!(ProductVariant::query()->select('code')->where('code', $code)->exists()));
    }
}
