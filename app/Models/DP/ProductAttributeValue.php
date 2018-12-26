<?php

namespace App\Models\DP;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductAttributeValue extends Pivot
{
    protected $table = 'product_attribute_values';

    protected $connection = 'dealpaw';

    protected $fillable = [
        'product_id',
        'attribute_id',
        'locale_code',
        'text_value',
        'boolean_value',
        'integer_value',
        'float_value',
        'datetime_value',
        'date_value',
        'json_value',
    ];

    public static $updateFillable = [
        'text_value',
        'boolean_value',
        'integer_value',
        'float_value',
        'datetime_value',
        'date_value',
        'json_value',
    ];

    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }
}
