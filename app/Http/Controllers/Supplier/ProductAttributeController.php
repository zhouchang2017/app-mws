<?php

namespace App\Http\Controllers\Supplier;

use App\Models\DP\ProductAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductAttributeController extends Controller
{
    public function index(Request $request)
    {
        return ProductAttribute::when($request->taxon, function ($query, $taxon) {
            $query->whereTaxon($taxon);
        })->get();
    }
}
