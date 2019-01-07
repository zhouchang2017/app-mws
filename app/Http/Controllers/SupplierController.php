<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resources\Supplier as SupplierResource;

class SupplierController extends Controller
{
    public static $resource = SupplierResource::class;
}
