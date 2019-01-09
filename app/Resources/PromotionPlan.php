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
        'supplier','promotion'
    ];

    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return '促销计划';
    }

    public function authorizedToIndex($request)
    {
        return [
            'canView' => true,
            'canUpdate' => true,
            'canCreate' => $request->isAdmin(),
            'canDestroy' => true,
            'canSearch' => true,
        ];
    }


}