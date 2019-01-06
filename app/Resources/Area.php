<?php

namespace App\Resources;


class Area extends Resource
{
    public static $model = \App\Models\Divisions\Area::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'city_id', 'province_id',
    ];

    public static $with = [

    ];

    public static $count = [];

    public static $filter = [ 'province_id', 'city_id' ];

    public static function label()
    {
        return '区域';
    }


}