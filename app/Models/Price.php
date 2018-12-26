<?php

namespace App\Models;

use App\Traits\MoneyFormatableTrait;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use MoneyFormatableTrait;

    protected $fillable = ['price'];

    public function priceable()
    {
        return $this->morphTo();
    }
}
