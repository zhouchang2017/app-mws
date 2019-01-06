<?php

namespace App\Resources;


class User extends Resource
{
    public static $model = \App\Models\User::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'email',
        'name',
    ];

    public static $with = [];


    public static $count = [];


    public static function label()
    {
        return '用户';
    }


}