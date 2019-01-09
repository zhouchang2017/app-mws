<?php

namespace App\Models;

use App\Traits\MoneyFormatableTrait;

class Price extends Model
{
    use MoneyFormatableTrait;

    protected $fillable = [ 'price' ];

    protected $connection = 'mysql';

    public function priceable()
    {
        return $this->morphTo();
    }
}
