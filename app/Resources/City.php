<?php

namespace App\Resources;


class City extends Resource
{
    public static $model = \App\Models\Divisions\City::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','province_id'
    ];

    public static $with = [

    ];

    public static $count = [];

    public static $filter = ['province_id'];

    public static function label()
    {
        return '城市';
    }


}