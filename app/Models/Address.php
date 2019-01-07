<?php

namespace App\Models;

use App\Models\Divisions\Area;
use App\Models\Divisions\City;
use App\Models\Divisions\Province;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed country
 * @property mixed province
 * @property mixed city
 * @property mixed district
 * @property mixed address
 * @property mixed name
 * @property mixed phone
 * @property mixed tel
 * @property mixed zip
 */
class Address extends Model
{
    protected $fillable = [
        'collection_name',
        'name',
        'tel',
        'phone',
        'fax',
        'zip',
        'country',
        'province',
        'city',
        'district',
        'address',
        'en',
    ];

    protected $casts = [
        'en' => 'array',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    public function getCascaderAttribute()
    {
        return [
            Province::query()->where('name', $this->province)->select(['id'])->pluck('id')->first(),
            City::query()->where('name', $this->city)->select(['id'])->pluck('id')->first(),
            Area::query()->where('name', $this->district)->select(['id'])->pluck('id')->first(),
        ];
    }

    public function simple()
    {
        return $this->country . ' ' . $this->province . ' ' . $this->city . ' ' . $this->district . ' ' . $this->address
            . ' 收件人:' . $this->name . ' 手机:' . $this->phone . ' 座机:' . $this->tel . ' 邮编:' . $this->zip;
    }
}
