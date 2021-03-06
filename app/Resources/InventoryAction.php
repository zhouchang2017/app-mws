<?php

namespace App\Resources;


use Illuminate\Http\Request as NovaRequest;

class InventoryAction extends Resource
{
    public static $model = \App\Models\InventoryAction::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $with = [
        'variant',
        'warehouse',
    ];

    public static $count = [];

    public static $filter = [
        'warehouse_id',
        'variant_id',
    ];

    public static function label()
    {
        return '库存';
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->latest('quantity');
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