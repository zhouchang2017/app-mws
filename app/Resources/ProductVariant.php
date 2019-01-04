<?php

namespace App\Resources;


use Illuminate\Http\Request as NovaRequest;

class ProductVariant extends Resource
{
    public static $model = \App\Models\DP\ProductVariant::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [];


    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return '变体';
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->when(request()->get('supplier', false), function ($query, $supplier) {
            $query->filterSupplier($supplier);
        });
    }


}