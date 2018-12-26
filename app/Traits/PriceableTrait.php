<?php

namespace App\Traits;


use App\Models\Price;

trait PriceableTrait
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function price()
    {
        return $this->morphOne(Price::class, 'priceable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function prices()
    {
        return $this->MorphMany(Price::class, 'priceable');
    }
}