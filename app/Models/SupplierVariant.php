<?php

namespace App\Models;

use App\Models\DP\ProductVariant;
use App\Observers\SupplierVariantObserver;
use App\Traits\MoneyFormatableTrait;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SupplierVariant extends Pivot
{
    use MoneyFormatableTrait;

    protected $connection = 'mysql';

    protected $table = 'supplier_variants';

    protected $fillable = [
        'variant_id',
        'supplier_id',
        'price',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(SupplierVariantObserver::class);
    }


    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }


    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'product_variant_id', 'product_variant_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function updateHiddenField($flag = 0)
    {
        $this->setAttribute('hidden', $flag);
        $this->save();
    }
}
