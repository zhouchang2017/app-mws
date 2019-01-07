<?php

namespace App\Services;


use App\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressService
{


    public static function updateOrCreateAddress(Model $addressable, array $data, $address = null)
    {
        return DB::transaction(function () use ($addressable, $data, $address) {
            return tap($address ?? new Address(),
                function ($instance) use ($data, $addressable) {

                    /** @var Address $instance */
                    $instance->fill($data);
                    $instance->addressable()->associate($addressable);
                    $instance->save();
                });
        });
    }


    public static function createAddresses(Model $addressable, Request $request)
    {

    }
}