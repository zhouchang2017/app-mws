<?php

namespace App\Models;

use App\Models\DP\Product;
use App\Models\DP\ProductVariant;
use App\Traits\AddressableTrait;
use Illuminate\Support\Facades\DB;

/**
 * @property mixed manager
 */
class Supplier extends Model
{
    use AddressableTrait;

    protected $connection = 'mysql';
    /**
     * @var array
     */
    protected $fillable = [
        'code',
        'level',
        'description',
        'user_id',
        'supplier_user_id',
    ];

    protected $appendAuthorizes = ['addUser'];

    public function officeAddress()
    {
        return $this->address()->where('collection_name', 'office');
    }

    public function warehouseAddress()
    {
        return $this->address()->where('collection_name', 'warehouse');
    }

    // 供应商管理员
    public function manager()
    {
        return $this->belongsTo(SupplierUser::class, 'supplier_user_id');
    }

    // 官方小二
    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 供应商员工 最多5个
    public function users()
    {
        return $this->hasMany(SupplierUser::class, 'supplier_id');
    }

    // 供应商产品
    public function products()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany(Product::class, $database . '.supplier_product');
    }

    public function getProductIdsAttribute()
    {
        return DB::table('supplier_product')->where('supplier_id', $this->id)->pluck('product_id')->toArray();
    }

    public function variants()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany(ProductVariant::class, $database . '.supplier_variants', 'supplier_id',
            'variant_id')->using(SupplierVariant::class)->withTimestamps()
            ->withPivot('name')->wherePivot('hidden', 0);
    }

    public function getVariantIdsAttribute()
    {
        return DB::table('supplier_variants')->where('supplier_id', $this->id)->pluck('variant_id')->toArray();
    }

}
