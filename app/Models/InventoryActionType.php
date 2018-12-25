<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryActionType extends Model
{
    const TAKE = 'take';
    const PUT = 'put';

    protected $fillable = [
        'name',
        'action',
        'is_accounting',
    ];

    protected $casts = [
        'is_ accounting' => 'bool',
    ];

    public static function actions()
    {
        return [
            static::TAKE => '出库',
            static::PUT  => '入库',
        ];
    }

    public function isTake()
    {
        return $this->action === static::TAKE;
    }

}
