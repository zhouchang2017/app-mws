<?php

namespace App\Resources;


class Province extends Resource
{
    public static $model = \App\Models\Divisions\Province::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    public static $with = [

    ];

    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return '省份';
    }


}