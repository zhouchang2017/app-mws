<?php

namespace App\Models;

use App\Models\DP\Product;
use App\Models\DP\ProductVariant;
use App\Observers\BillObserver;
use App\Scopes\SupplierBillScope;
use App\Traits\HasStatuses;
use App\Traits\MoneyFormatableTrait;

/**
 * @property mixed confirm_at
 * @property mixed supplier
 */
class Bill extends Model
{
    use MoneyFormatableTrait, HasStatuses;

    const NOT_ACTIVE = 'not_active'; // 初始化，暂未生效
    const ACTIVE = 'active'; // 以生效
    const CONFIRMED = 'confirmed'; // 以核对
    const CANCEL = 'cancel'; // 以取消
//    const CASHING = 'cashing'; // 申请提现
//    const CASHED = 'cashed'; // 以提现

    protected $appends = ['current_state'];

    protected $with = ['state'];

    protected $fillable = [
        'number',
        'product_id',
        'variant_id',
        'supplier_id',
        'quantity',
        'origin_price',
        'price',
        'rest',
    ];

    protected $casts = [
        'rest' => 'array',  // 冗余优惠明细 type=> '', amount=>-/+
        'confirm_at' => 'datetime',
    ];


    protected static function boot()
    {
        parent::boot();
        static::observe(BillObserver::class);
        static::addGlobalScope(new SupplierBillScope());
    }

    public function getCurrentStateAttribute()
    {
        $status = [
            self::NOT_ACTIVE => '初始化未生效',
            self::ACTIVE => '以生效',
            self::CONFIRMED => '已核对',
        ];
        return array_get($status, optional($this->state)->name, 'N/A');
    }


    public function origin()
    {
        return $this->morphTo();
    }

    public function isConfirmed()
    {
        return is_null($this->confirm_at);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getOriginPriceAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

    public function setOriginPriceAttribute($value)
    {
        $this->attributes['origin_price'] = $this->saveCurrencyUsing($value === 0 ? '0.00' : (string)$value);
    }

    public function isValidStatus(string $name, ?string $reason = null): bool
    {
        return $this->status !== $name;
    }
}
