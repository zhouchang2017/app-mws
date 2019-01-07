<?php

namespace App\Resources;


class PromotionPlan extends Resource
{
    public static $model = \App\Models\DP\PromotionPlan::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [
        'supplier'
    ];

    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return '促销计划';
    }

    public function authorizedToIndex()
    {
        return [
            'canView' => true,
            'canUpdate' => true,
            'canCreate' => true,
            'canDestroy' => true,
            'canSearch' => true,
        ];
    }


}