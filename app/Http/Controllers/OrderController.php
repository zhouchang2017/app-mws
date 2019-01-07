<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resources\Order as OrderResource;

class OrderController extends Controller
{
    public static $resource = OrderResource::class;
}
