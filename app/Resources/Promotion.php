<?php

namespace App\Resources;


class Promotion extends Resource
{
    public static $model = \App\Models\DP\Promotion::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [
        'plans','channels'
    ];

    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return '促销活动';
    }

    public function authorizedToIndex()
    {
        return [
            'canView' => true,
            'canUpdate' => false,
            'canCreate' => false,
            'canDestroy' => false,
            'canSearch' => true,
        ];
    }


}