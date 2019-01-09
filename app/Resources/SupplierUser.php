<?php

namespace App\Resources;


class SupplierUser extends Resource
{
    public static $model = \App\Models\SupplierUser::class;

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
        return '供应商用户';
    }


}