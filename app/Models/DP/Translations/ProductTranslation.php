<?php

namespace App\Models\DP\Translations;


use App\Models\DP\Model;

class ProductTranslation extends Model
{
    protected $fillable = [
        'name',
        'short_description',
        'description',
        'slug',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];
}
