<?php

namespace App\Resources;


use App\Models\User;

class Supply extends Resource
{
    public static $model = \App\Models\Supply::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [
        'origin'
    ];


    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        if(auth()->user() instanceof User){
            return '供应商入库';
        };
        return '供货计划';
    }


}