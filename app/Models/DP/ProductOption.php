<?php

namespace App\Models\DP;


use Dimsav\Translatable\Translatable;

class ProductOption extends Model
{
    use Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable = ['code', 'position', 'taxon_id'];

    public function values()
    {
        return $this->hasMany(ProductOptionValue::class, 'option_id');
    }

    public function scopeWhereTaxon($query, $id)
    {
        return $query->where('taxon_id', $id);
    }

    public function taxon()
    {
        return $this->belongsTo(Taxon::class);
    }
}
