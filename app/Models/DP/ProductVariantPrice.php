<?php

namespace App\Models\DP;

use App\Traits\MoneyFormatableTrait;
use Illuminate\Database\Eloquent\Builder;

class ProductVariantPrice extends Model
{
    use MoneyFormatableTrait;

    protected $fillable = ['variant_id', 'channel_id', 'original_price', 'price'];

    public static $updateFillable = ['original_price', 'price'];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function scopeFilterChannel(Builder $query, $channel)
    {
        $query->where('channel_id', $channel);
    }

    public function getOriginalPriceAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

    public function setOriginalPriceAttribute($value)
    {
        $this->attributes['original_price'] = $this->saveCurrencyUsing($value === 0 ? '0.00' : (string)$value);
    }


}
