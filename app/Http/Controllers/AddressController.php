<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resources\Address as AddressResource;

class AddressController extends Controller
{
    public static $resource = AddressResource::class;

    public function create()
    {
        $this->viewShare();
        return view('addresses.create');
    }
}
