<?php

namespace App\Models;

use App\Models\DP\ProductVariant;
use App\Observers\PreInventoryActionOrderItemStateObserver;
use App\Traits\AttachmentableTrait;

/**
 * Class PreInventoryActionOrderItemState
 * @property mixed item
 * @package App\Models
 */
class PreInventoryActionOrderItemState extends Model
{
    use AttachmentableTrait;
    /**
     * @var array
     */
    protected $fillable = ['warehouse_area', 'type_id', 'quantity', 'variant_id'];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();
        static::observe(PreInventoryActionOrderItemStateObserver::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(PreInventoryActionOrderItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(InventoryActionType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * @return Warehouse
     */
    public function getWarehouseAttribute()
    {
        return $this->item->preOrder->warehouse;
    }

    public function getProductIdAttribute()
    {
        return $this->item->product_id;
    }

    /**
     * 来源 supply/order
     * @return mixed
     */
    public function getOriginAttribute()
    {
        return $this->item->preOrder->PreInventoryAction->origin->origin;
    }

    public function addAttachment(array $data)
    {
        return $this->attachments()->create($data);
    }

    public function addAttachments(array $data)
    {
        return $this->attachments()->createMany($data);
    }

}
