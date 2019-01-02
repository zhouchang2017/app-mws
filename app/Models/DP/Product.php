<?php

namespace App\Models\DP;


use App\Scopes\SupplierProductScope;
use App\Traits\ProductCheckStatus;
use Dimsav\Translatable\Translatable;

class Product extends Model
{
    use Translatable, ProductCheckStatus;

    const UN_COMMIT = 'saved';
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const POSTPONED = 'postponed';

    public $translatedAttributes = [
        'name',
        'short_description',
        'description',
        'slug',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];

    protected $fillable = ['code', 'taxon_id', 'enabled'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SupplierProductScope());
    }

    public function taxon()
    {
        return $this->belongsTo(Taxon::class);
    }


    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(ProductAttribute::class, 'product_attribute_values', 'product_id', 'attribute_id')
            ->withPivot('id', 'locale_code', 'text_value', 'boolean_value', 'float_value', 'datetime_value',
                'json_value')
            ->as('value')
            ->using(ProductAttributeValue::class);
    }

    public function getAttributeValuesTranslationAttribute()
    {
        return $this->attributeValues->groupBy('attribute_id')
            ->map(function ($attr) {
                return $attr->groupBy('locale_code')->map(function ($prop) {
                    return $prop->first();
                });
            });
    }


    public function options()
    {
        return $this->belongsToMany(ProductOption::class, 'product_product_option', 'product_id', 'option_id');
    }

    public function optionValues()
    {
        return $this->hasManyThrough(
            ProductVariantOptionValue::class,
            ProductVariant::class,
            'product_id',
            'variant_id'
        );
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function variant()
    {
        return $this->hasOne(ProductVariant::class)->groupBy('product_id');
    }
}
