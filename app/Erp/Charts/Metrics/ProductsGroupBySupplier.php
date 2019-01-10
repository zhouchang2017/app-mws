<?php

namespace App\Erp\Charts\Metrics;

use App\Erp\Metrics\Partition;
use App\Models\DP\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductsGroupBySupplier extends Partition
{
    public $name = '供应商产品归类';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        $dbName = config('database.connections.mysql.database');
        $build = Product::query()->join($dbName.'.supplier_product','product_id','=','id');
        $supplier = Supplier::all();

        return $this->count($request, $build, 'supplier_product.supplier_id')->label(function ($value)use($supplier) {
            return optional($supplier->find($value))->name;
        });
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'users-per-plan';
    }
}
