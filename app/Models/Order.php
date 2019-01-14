<?php

namespace App\Models;

use App\Models\DP\Channel;
use App\Services\OrderService;
use App\Traits\HasStatuses;
use App\Traits\MoneyFormatableTrait;
use Spatie\ModelStatus\Exceptions\InvalidStatus;

/**
 * @property mixed origin
 * @property mixed description
 * @property mixed status
 * @property mixed preInventoryAction
 * @property mixed market
 * @property mixed items
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
        'market_id',
        'price',
        'created_at',
        'updated_at',
    ];


    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'type_name','current_state'
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $service = new OrderService($model);
            $service->createOrderItems();
            // $service->createPreInventoryAction();
        });
    }

    public function getCurrentStateAttribute()
    {
        $status = [
            self::PENDING => '等待买家付款',
            self::UN_SHIP => '待发货',
            self::PART_SHIPPED => '部分发货',
            self::SHIPPED => '已发货',
            self::CANCEL => '已取消',
            self::UNFULFILLABLE => '订单无法进行配送'
        ];
        return array_get($status, $this->state->name, 'N/A');
    }

    // 预出\入库(入库单\出货单)
    public function preInventoryAction()
    {
        return $this->morphOne(PreInventoryAction::class, 'origin');
    }

    /**
     * @param string $name
     * @param null|string $reason
     * @return Order
     * @throws InvalidStatus
     */
    public function setStatus(string $name, ?string $reason = null): self
    {
        if ($name === $this->status) {
            return $this;
        }
        if ( !$this->isValidStatus($name, $reason)) {
            throw InvalidStatus::create($name);
        }

        return $this->forceSetStatus($name, $reason);
    }


    public function origin()
    {
        return $this->morphTo();
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function loadItemsWithMarketPrice()
    {
        $this->loadMissing(['market', 'items.variant']);
        if ($this->market->marketable instanceof Channel) {

            $this->items->each(function($item){
                $item->variant->appendCurrentPrice($this->market->marketable);
            });
        }
        return $this;
    }

    public function address()
    {
        return $this->origin->address;
    }

    public function getSimpleAddressAttribute()
    {
        return $this->origin->address->simple_address;
    }

    public function getTypeNameAttribute()
    {
        return app($this->{$this->origin()->getMorphType()})::$orderTypeName;
    }

    // ====================================================================================== //

    public function getDescriptionAttribute()
    {
        return $this->origin->description;
    }

    public function getExpendItems()
    {
        return $this->origin->getExpendItems();
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}
