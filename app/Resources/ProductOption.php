<?php

namespace App\Resources;


class ProductOption extends Resource
{
    public static $model = \App\Models\DP\ProductOption::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [
        'values'
    ];


    public static $count = [];

    public static $filter = ['taxon_id'];

    public static function label()
    {
        return '产品销售属性';
    }


}