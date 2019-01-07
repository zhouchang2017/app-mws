<?php

namespace App\Http\Controllers;

use App\Models\DP\Promotion;
use Illuminate\Http\Request;
use App\Resources\Promotion as PromotionResource;

class PromotionController extends Controller
{

    public static $resource = PromotionResource::class;


    /**
     * Display the specified resource.
     *
     * @param Promotion $promotion
     * @return \Illuminate\Http\Response
     */
    public function show(Promotion $promotion)
    {
        $resource = $promotion;
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare((new static::$resource(static::$resource::newModel()))->authorizedToIndex());
        return view(static::$resource::uriKey() . '.detail', compact('resource'));
    }

}
