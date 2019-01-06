<?php

namespace App\Resources;


class WarehouseType extends Resource
{
    public static $model = \App\Models\WarehouseType::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];

    public static $with = [];


    public static $count = [];


    public static function label()
    {
        return '仓库类型';
    }


}