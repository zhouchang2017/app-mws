<?php

namespace App\Models\DP;


use App\Traits\MarketableTrait;

class Channel extends Model
{
    use MarketableTrait;

    protected $fillable = ['code', 'enabled', 'locale_code', 'currency_code', 'name', 'description', 'email'];

    public static $marketName = 'DP商店';

    public function getMarketableTypeAttribute()
    {
        return get_class($this);
    }

    public function getMarketableIdAttribute()
    {
        return $this->id;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'channel_product');
    }
}
