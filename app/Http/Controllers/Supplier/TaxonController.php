<?php

namespace App\Http\Controllers\Supplier;

use App\Models\DP\Taxon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaxonController extends Controller
{
    public function index()
    {
        return response()->json(Taxon::all()->toTree());
    }
}
