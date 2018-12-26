<?php

namespace App\Models\DP;


use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use SoftDeletes, Translatable;

    public $translatedAttributes = ['name', 'description', 'rest', 'asset_url'];

    protected $casts = [
        'configuration' => 'array',
        'supplier' => 'array',
        'began_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    protected $fillable = [
        'code',
        'position',
        'exclusive',
        'type',
        'long_term',
        'began_at',
        'ended_at',
        'configuration',
        'supplier',
    ];

    const KOL_PROMOTE = 'kol_promote'; // kol promote
    const UNIT_DISCOUNT = 'unit_discount'; // 直减

    const FULL_DISCOUNT = 'full_discount'; // 满减
    const CASH_COUPON = 'cash_coupon'; // 代金券

    protected $appends = ['type_name'];

    public static function typeMaps()
    {
        return [
            static::KOL_PROMOTE => '红人推广活动',
            static::UNIT_DISCOUNT => '直减活动',
            static::FULL_DISCOUNT => '满减活动',
            static::CASH_COUPON => '代金券活动',
        ];
    }

    public function getTypeNameAttribute()
    {
        return static::typeMaps()[$this->type];
    }

    public function scopeKolPromote($query)
    {
        return $query->where('promotion_type', static::KOL_PROMOTE)
            ->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('began_at', '<', $this->freshTimestampString())
            ->where('ended_at', '>', $this->freshTimestampString());
    }

    public function scopeWhenType()
    {

    }

    public function activeVariants()
    {
        return $this->variants()
            ->wherePivot('began_at', '<', $this->freshTimestampString())
            ->wherePivot('ended_at', '>', $this->freshTimestampString());
    }

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'promotion_variants', 'promotion_id', 'variant_id');
    }

    public function signUp()
    {
        return $this->hasOne(PromotionSignUp::class, 'promotion_id')->groupBy('promotion_id');
    }

    public function promotionVariants()
    {
        return $this->hasMany(PromotionVariant::class);
    }
}
