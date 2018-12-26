<?php

namespace App\Models;

use App\Contracts\Addressable;
use App\Traits\AddressableTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Warehouse
 * @package App\Models
 */
class Warehouse extends Model implements Addressable
{
    use AddressableTrait;
    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];

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
}
