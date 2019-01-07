<?php

namespace App\Models;

use App\Models\DP\Channel;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $fillable = [
        'name',
        'description',
        'enabled',
    ];

    protected $connection = 'mysql';

    protected $appends = ['type_name'];

    public static $marketables = [
        Channel::class,
    ];

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
