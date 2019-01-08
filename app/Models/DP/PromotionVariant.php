<?php

namespace App\Models\DP;


class PromotionVariant extends Model
{
    protected $fillable = [
        'variant_id',
        'product_id',
        'promotion_id',
        'promotion_type',
        'surrender_rate',
        'discount_rate',
        'quantity_limit',
        'stock',
        'sold',
        'began_at',
        'ended_at',
        'rest',
        'plan_id',
    ];

    protected static function boot()
    {
        parent::boot();
    }


    public function getSurrenderRateAttribute($value)
    {
        return $value * 100;
    }

    public function setSurrenderRateAttribute($value)
    {
        info('set surrender rate '.$value);
        $this->attributes['surrender_rate'] = $value * 0.01;
    }

    public function getDiscountRateAttribute($value)
    {
        return $value * 100;
    }

    public function setDiscountRateAttribute($value)
    {
        $this->attributes['discount_rate'] = $value * 0.01;
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function plan()
    {
        return $this->belongsTo(PromotionPlan::class);
    }
}
