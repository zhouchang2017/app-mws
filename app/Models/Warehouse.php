<?php

namespace App\Models;

use App\Contracts\Addressable;
use App\Traits\AddressableTrait;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Warehouse
 * @property mixed address
 * @package App\Models
 */
class Warehouse extends Model implements Addressable
{
    use AddressableTrait;
    /**
     * @var array
     */
    protected $fillable = ['name', 'type_id', 'user_id'];

    /**
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        self::addGlobalScope('countInventories', function (Builder $builder) {
            $builder->withCount([
                'inventories' => function ($query) {
                    /** @var Builder $query */
                    if (isSupplier()) {
                        $query->whereIn('variant_id', auth()->user()->supplier->variantIds);
                    }
                        $query->select('quantity');

                },
            ]);
        });
    }


    /**
     * 仓库类型
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(WarehouseType::class, 'type_id');
    }

    /**
     * 仓库管理员
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }


}
