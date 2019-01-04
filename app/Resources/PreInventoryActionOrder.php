<?php

namespace App\Resources;


class PreInventoryActionOrder extends Resource
{
    public static $model = \App\Models\PreInventoryActionOrder::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [
        'warehouse',
        'type',
    ];

    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return '操作单';
    }


}