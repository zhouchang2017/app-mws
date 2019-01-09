<?php

namespace App\Models\DP;


use App\Models\Activity;
use App\Models\Supplier;
use App\Models\User;
use App\Notifications\ConfirmPromotionPlanNotification;
use App\Scopes\SupplierPromotionPlanScope;
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

    protected $casts = [
        'confirm_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        self::addGlobalScope(new SupplierPromotionPlanScope());
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


    public function markAsConfirm()
    {
        if (is_null($this->confirm_at)) {
            activity()
                ->performedOn($this)
                ->causedBy(auth()->user())
                ->log('供应商确认计划');
            $this->forceFill(['confirm_at' => $this->freshTimestamp()])->save();

            // 供应商接收计划邀请

            User::all()->each->notify(new ConfirmPromotionPlanNotification($this));
        }
    }
}
