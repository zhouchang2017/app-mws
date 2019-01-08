<?php

namespace App\Models\DP;


use App\Models\Activity;
use App\Models\Supplier;
use Illuminate\Support\Collection;

/**
 * @property mixed promotion
 */
class PromotionPlan extends Model
{
    protected $fillable = [
        'supplier_id',
        'promotion_id',
        'state',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }


    public function promotionVariants()
    {
        return $this->hasMany(PromotionVariant::class, 'plan_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * 邀请记录
     * @return Collection
     */
    public function inviteLogs()
    {
        return $this->morphMany(Activity::class, 'subject')->inLog('invite');
    }
}
