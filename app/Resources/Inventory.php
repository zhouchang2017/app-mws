<?php

namespace App\Resources;


use Illuminate\Http\Request as NovaRequest;

class Inventory extends Resource
{
    public static $model = \App\Models\Inventory::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [
        'variant', 'warehouse',
    ];

    public static $count = [];

    public static $filter = [];

    public static function label()
    {
        return '库存';
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->latest('quantity');
    }

    public function authorizedToIndex()
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