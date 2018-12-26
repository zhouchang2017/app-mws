<?php

namespace App\Models\DP;


use Dimsav\Translatable\Translatable;

class ProductOptionValue extends Model
{
    use Translatable;

    public $translatedAttributes = ['value'];

    protected $fillable = ['code', 'option_id'];

    protected $appends = ['unique_code'];

    public function getUniqueCodeAttribute()
    {
        return md5($this->value);
    }

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id');
    }

    public function variantOption()
    {
        return $this->hasMany(ProductVariantOptionValue::class, 'value_id');
    }
}
