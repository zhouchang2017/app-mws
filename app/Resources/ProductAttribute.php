<?php

namespace App\Resources;


class ProductAttribute extends Resource
{
    public static $model = \App\Models\DP\ProductAttribute::class;

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

    public static $filter = ['taxon_id'];

    public static function label()
    {
        return '产品属性';
    }


}