<?php

namespace App\Resources;


use Illuminate\Http\Request as NovaRequest;

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


    public static $count = ['inventories:quantity'];


    public static function label()
    {
        return '仓库';
    }

}