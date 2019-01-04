<?php

namespace App\Resources;


class PreInventoryAction extends Resource
{
    public static $model = \App\Models\PreInventoryAction::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [
        'type'
    ];

    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return '入库单\出货单';
    }


}