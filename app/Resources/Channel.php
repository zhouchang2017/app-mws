<?php

namespace App\Resources;


class Channel extends Resource
{
    public static $model = \App\Models\DP\Channel::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'code',
    ];

    public static $with = [

    ];

    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return 'DP多商店';
    }


}