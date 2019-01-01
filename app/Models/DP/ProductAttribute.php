<?php

namespace App\Models\DP;

use Dimsav\Translatable\Translatable;

class ProductAttribute extends Model
{
    use Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable = ['code', 'taxon_id', 'type', 'storage_type', 'configuration', 'position'];

    public function scopeWhereTaxon($query, $id)
    {
        return $query->where('taxon_id', $id);
    }

    public function taxon()
    {
        return $this->belongsTo(Taxon::class);
    }
}
