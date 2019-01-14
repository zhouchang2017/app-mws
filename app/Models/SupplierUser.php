<?php

namespace App\Models;


use App\Models\DP\PromotionPlan;
use App\Notifications\InvitePromotionPlanNotification;
use App\Traits\Authorizable;
use App\Traits\Notifiable;
use App\Traits\WechatableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SupplierUser extends Authenticatable
{
    use Notifiable, WechatableTrait,Authorizable;

    protected $allowAuthorizes = ['update','view','destroy'];

    protected $appendAuthorizes = [];

    protected static function boot()
    {
        parent::boot();
        static::retrieved(function($model){
            $model->append('authorize');
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'supplier_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products()
    {
        return $this->supplier->products;
    }

    public function variants()
    {
        return $this->supplier->variants;
    }

    public function invitePromotionPlanNotify(PromotionPlan $promotionPlan, $title, $body)
    {
        $this->notify(new InvitePromotionPlanNotification($promotionPlan, $title, $body));
    }
}
