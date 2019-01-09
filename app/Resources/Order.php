<?php

namespace App\Resources;


class Order extends Resource
{
    public static $model = \App\Models\Order::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [
        'market'
    ];

    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return '订单';
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