<?php

namespace App\Resources;


class Bill extends Resource
{
    public static $model = \App\Models\Bill::class;


    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [
        'product','variant','supplier'
    ];

    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return '账单';
    }


    public function authorizedToIndex($request)
    {
        return [
            'canView' => true,
            'canUpdate' => true,
            'canCreate' => false,
            'canDestroy' => true,
            'canSearch' => false,
        ];
    }


}