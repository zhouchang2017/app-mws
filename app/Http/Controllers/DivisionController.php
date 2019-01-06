<?php

namespace App\Http\Controllers;

use App\Http\Resources\AreaResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\ProvinceResource;
use App\Models\Divisions\Province;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function search()
    {

    }

    public function provinces()
    {
        static::$resource = \App\Resources\Province::class;
        return response()->json(
            ProvinceResource::collection($this->indexQuery()->get())
        );
    }

    public function cities()
    {
        static::$resource = \App\Resources\City::class;
        return response()->json(
            CityResource::collection($this->indexQuery()->get())
        );
    }

    public function areas()
    {
        static::$resource = \App\Resources\Area::class;
        return response()->json(
            AreaResource::collection($this->indexQuery()->get())
        );
    }
}
