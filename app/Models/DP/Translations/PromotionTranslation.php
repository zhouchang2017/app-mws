<?php

namespace App\Models\DP\Translations;


use App\Models\DP\Model;

class PromotionTranslation extends Model
{
    protected $fillable = [
        'description',
        'name',
        'rest',
        'locale_code',
        'translatable_id',
        'asset_url',
        'asset_iamge'
    ];
}
