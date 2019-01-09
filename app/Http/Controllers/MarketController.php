<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErpRequest;
use App\Models\Market;
use Illuminate\Http\Request;
use App\Resources\Market as MarketResource;

class MarketController extends Controller
{
    public static $resource = MarketResource::class;

    public function getMarketables()
    {
        return response()->json(
            Market::marketableMaps()
        );
    }

    public function show(Market $market)
    {
        $resource = $market->loadMissing(['marketable']);
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare();
        return view(static::$resource::uriKey() . '.detail', compact('resource'));
    }

    public function create()
    {
        $this->viewShare();
        return view(static::$resource::uriKey() . '.create');
    }

    public function store(ErpRequest $request)
    {
        $market = new Market();
        $market->fill($request->all());
        $associate = app($request->get('marketable_type'))->find($request->get('marketable_id'));
        $market->marketable()->associate($associate);
        $market->save();
        return $this->created(
            $market
        );
    }

    public function edit(Market $market)
    {
        $resource = $market->loadMissing(['marketable']);
        $resource->marketable->append(['marketable_type','marketable_id']);
        $this->viewShare();
        return view(static::$resource::uriKey() . '.update', compact('resource'));
    }

    public function update(Market $market, ErpRequest $request)
    {
        $market->fill($request->all());
        $associate = app($request->get('marketable_type'))->find($request->get('marketable_id'));
        $market->marketable()->associate($associate);
        $market->save();
        return $this->updated($market);
    }
}
