<?php

namespace App\Models\DP\Translations;


use App\Models\DP\Model;
use App\Models\DP\Taxon;

class TaxonTranslation extends Model
{
    protected $fillable = ['name', 'description', 'slug', 'locale_code', 'translatable_id'];

    protected static $updateFillable = ['name', 'description', 'slug'];

    public function taxon()
    {
        return $this->belongsTo(Taxon::class, 'translatable_id');
    }
}
