<?php

namespace App\Http\Controllers\Supplier;

use App\Models\DP\ProductVariant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductVariantController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return response()->json(ProductVariant::paginate(15));
        }
        return view('supplier.pages.variants.index');
    }
}
