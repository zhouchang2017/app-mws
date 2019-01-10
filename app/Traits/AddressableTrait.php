<?php

namespace App\Traits;


use App\Models\Address;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Trait AddressableTrait
 * @package Chang\Erp\Traits
 */
trait AddressableTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function addressByCollection($collectionName)
    {
        return $this->morphOne(Address::class, 'addressable')
            ->where('collection_name', $collectionName);
    }

    public function scopeAddressWhereCollection($query, $collectionName)
    {
        $query->with(['address'=>function($query)use($collectionName){
            $query->where('collection_name', $collectionName);
        }]);
    }

    public function addressByCollections($collectionName)
    {
        return $this->morphMany(Address::class, 'addressable')
            ->where('collection_name', $collectionName);
    }

    public function getSimpleAddressAttribute()
    {
        return $this->address->simple();
    }


}