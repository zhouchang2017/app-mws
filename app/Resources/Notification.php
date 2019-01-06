<?php

namespace App\Resources;


use Illuminate\Notifications\DatabaseNotification;

class Notification extends Resource
{
    public static $model = DatabaseNotification::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [

    ];

    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return '附加费用调整类型';
    }


}