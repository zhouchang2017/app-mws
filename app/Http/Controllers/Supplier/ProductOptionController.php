<?php

namespace App\Http\Controllers\Supplier;

use App\Models\DP\ProductOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductOptionController extends Controller
{
    public function index(Request $request)
    {
        return ProductOption::when($request->taxon, function ($query, $taxon) {
            $query->whereTaxon($taxon);
        })->with('values')->get();
    }
}
