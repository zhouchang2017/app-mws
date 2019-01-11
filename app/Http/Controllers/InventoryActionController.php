<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resources\InventoryAction as InventoryActionResource;

class InventoryActionController extends Controller
{
    public static $resource = InventoryActionResource::class;
}
