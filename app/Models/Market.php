<?php

namespace App\Models;

use App\Models\DP\Channel;

class Market extends Model
{
    protected $fillable = [
        'name',
        'description',
        'enabled',
    ];

    protected $connection = 'mysql';

    protected $appends = ['type_name'];

    protected $casts = [
        'enabled' => 'boolean'
    ];

    public static $marketables = [
        Channel::class,
    ];

    public static function marketableMaps()
    {
        return collect(static::$marketables)->map(function ($marketable) {
            return [
                'type' => $marketable::$marketName,
                'values' => $marketable::all()->each(function ($item){
                    $item->marketable_type = get_class($item);
                    $item->marketable_id = $item->id;
                }),
            ];
        });
    }

    public function marketable()
    {
        return $this->morphTo();
    }

    public function getTypeNameAttribute()
    {
        return app($this->{$this->marketable()->getMorphType()})::$marketName;
    }

    public function marketableUrl()
    {

    }


}
