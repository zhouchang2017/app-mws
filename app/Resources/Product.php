<?php

namespace App\Resources;


use App\Erp\Charts\Metrics\NewProducts;
use App\Erp\Charts\Metrics\ProductsGroupBySupplier;

class Product extends Resource
{
    public static $model = \App\Models\DP\Product::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'code',
    ];

    public static $with = [
        'image'
    ];

    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return '产品';
    }

    public function metrics($request)
    {
        return [
            new ProductsGroupBySupplier(),
            new NewProducts()
        ];
    }


}