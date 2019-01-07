<?php

namespace App\Resources;


class Market extends Resource
{
    public static $model = \App\Models\Market::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [

    ];

    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return '渠道';
    }


}