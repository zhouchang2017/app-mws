<?php

namespace App\Models\DP;


class Video extends Model
{
    public $fillable = [
        'enabled',
        'cover',
        'description',
        'position',
        'product_id',
        'short_length',
        'short_size',
        'short_video',
        'size',
        'type',
        'user_id',
        'video',
        'locale_code'
    ];

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
