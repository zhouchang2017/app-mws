<?php

namespace App\Models\DP;

/**
 * @property mixed country_code
 * @property mixed province_name
 * @property mixed city
 * @property mixed street
 * @property mixed first_name
 * @property mixed last_name
 * @property mixed phone_number
 * @property mixed postcode
 */
class Address extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'postcode',
        'country_code',
        'province_code',
        'province_name',
        'city',
        'street',
        'user_id'
    ];
    public static $updateFillable = [
        'first_name',
        'last_name',
        'phone_number',
        'postcode',
        'country_code',
        'province_code',
        'province_name',
        'city',
        'street',
    ];

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function getSimpleAddressAttribute()
    {
        return $this->country_code . ' ' . $this->province_name . ' ' . $this->city . ' ' . $this->street . ' '
            . ' 收件人:' . $this->first_name . ' ' . $this->last_name . ' 手机:' . $this->phone_number . ' 邮编:' . $this->postcode;
    }
}
