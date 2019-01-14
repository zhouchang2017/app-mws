<?php

namespace App\Http\Requests;

use App\Erp\Erp;
use App\Erp\Metrics\Metric;

class DashboardMetricRequest extends ErpRequest
{
    /**
     * Get the metric instance for the given request.
     *
     * @return Metric
     */
    public function metric()
    {
        return $this->availableMetrics()->first(function ($metric) {
            return $this->metric === $metric->uriKey();
        }) ?: abort(404);
    }

    /**
     * Get all of the possible metrics for the request.
     *
     * @return \Illuminate\Support\Collection
     */
    public function availableMetrics()
    {
        return Erp::availableDashboardCards($this)->whereInstanceOf(Metric::class);
    }
}
