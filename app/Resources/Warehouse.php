<?php

namespace App\Resources;


class Warehouse extends Resource
{
    public static $model = \App\Models\Warehouse::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];

    public static $with = ['type'];


    public static $count = ['inventories'];


    public static function label()
    {
        return '仓库';
    }


}