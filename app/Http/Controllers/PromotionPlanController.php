<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErpRequest;
use Illuminate\Http\Request;
use App\Resources\PromotionPlan as PromotionPlanResource;

class PromotionPlanController extends Controller
{
    public static $resource = PromotionPlanResource::class;

    /**
     * Show the form for creating a new resource.
     *
     * @param ErpRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(ErpRequest $request)
    {
        $this->viewShare();
        return view(static::$resource::uriKey() . '.create');
    }
}
