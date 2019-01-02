<?php

namespace App\Models;

use App\Traits\HasStatuses;
use App\Traits\MoneyFormatableTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed origin
 * @property mixed description
 */
class Order extends Model
{
    use MoneyFormatableTrait, HasStatuses;

    const PENDING = 'PENDING';              //等待买家付款
    const UN_SHIP = 'UN_SHIP';            //买家已付款，等待卖家发货
    const PART_SHIPPED = 'PART_SHIPPED';    //部分发货
    const SHIPPED = 'SHIPPED';              //已发货
    const CANCEL = 'CANCEL';               //订单已取消
    const UNFULFILLABLE = 'UNFULFILLABLE';       // 订单无法进行配送

    public $timestamps = false;

    protected $fillable = [
        'origin_id',
        'origin_type',
        'price',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function($model){

        });
    }


    public function origin()
    {
        return $this->morphTo();
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function address()
    {
        return $this->origin->address;
    }

    public function getSimpleAddressAttribute()
    {
        return $this->origin->address->simple_address;
    }

    // ====================================================================================== //
    public function getItemsAttribute()
    {
        return $this->origin->getExpendItems();
    }

    public function getDescriptionAttribute()
    {
        return $this->origin->description;
    }

}
