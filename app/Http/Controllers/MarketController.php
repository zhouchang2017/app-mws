<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resources\Market as MarketResource;

class MarketController extends Controller
{
    public static $resource = MarketResource::class;
}
