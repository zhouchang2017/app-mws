<?php

namespace App\Http\Controllers\Supplier;

use App\Models\DP\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return response()->json(Product::latest('updated_at')->paginate(15));
        }
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
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
        $resource = $product->loadMissing(['attributeValues.attribute', 'options', 'taxon', 'variants']);
        if (request()->ajax()) {
            return response()->json($resource);
        }
        return view('products.detail', compact('resource'));
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
        return view('products.update', compact('resource'));
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
}
