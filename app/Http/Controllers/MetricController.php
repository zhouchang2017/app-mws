<?php

namespace App\Http\Controllers;

use App\Http\Requests\MetricRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MetricController extends Controller
{
    /**
     * List the metrics for the given resource.
     *
     * @param MetricRequest $request
     * @return \Illuminate\Support\Collection
     */
    public function index(MetricRequest $request)
    {
        return $request->availableMetrics();
    }

    /**
     * Get the specified metric's value.
     *
     * @param MetricRequest $request
     * @return \Illuminate\Http\Response
     */
    public function show(MetricRequest $request)
    {
        return response()->json([
            'value' => $request->metric()->resolve($request),
        ]);
    }
}
