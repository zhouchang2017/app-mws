<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErpRequest;
use App\Models\DP\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public static $resource = \App\Resources\Product::class;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create',Product::class);
        return view(static::$resource::uriKey() . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->created(
            ProductService::updateOrCreateProduct($request)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $resource = $product->loadMissing(['attributeValues.attribute', 'options', 'taxon', 'variants.price']);
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare();
        return view(static::$resource::uriKey() . '.detail', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $resource = $product->loadMissing(['taxon', 'attributeValues', 'options:option_id']);
        $product->taxon->append('ancestors');
        $this->viewShare();
        return view(static::$resource::uriKey() . '.update', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        return $this->updated(
            ProductService::updateOrCreateProduct($request, $product)
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

    public function options(Product $product)
    {
        return response()->json(
            $product->options()->with('values')->get()
        );
    }

    public function submit(Product $product, ErpRequest $request)
    {
        $product->statusToPending();
        if (request()->ajax()) {
            return $this->updated([], '产品已提交审核', '您的提交会尽快处理,请耐心等待');
        } else {
            return redirect()->route($request->getSubDomain() . '.products.show', [$product]);
        }
    }

    public function approved(Product $product, ErpRequest $request)
    {
        $flag = (int)$request->get('approved', 1);
        $flag ? $product->statusToApproved() : $product->statusToRejected();
        if (request()->ajax()) {
            return $this->updated([], $flag ? '审核通过' : '审核拒绝');
        } else {
            return redirect()->route($request->getSubDomain() . '.products.show', [$product]);
        }
    }

}
