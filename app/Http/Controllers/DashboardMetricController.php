<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardMetricRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardMetricController extends Controller
{
    /**
     * List the metrics for the dashboard.
     *
     * @param DashboardMetricRequest $request
     * @return \Illuminate\Support\Collection
     */
    public function index(DashboardMetricRequest $request)
    {
        return $request->availableMetrics();
    }

    /**
     * Get the specified metric's value.
     *
     * @param DashboardMetricRequest $request
     * @return \Illuminate\Http\Response
     */
    public function show(DashboardMetricRequest $request)
    {
        return response()->json([
            'value' => $request->metric()->resolve($request),
        ]);
    }
}
