<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wechat extends Model
{
    protected $fillable = [
        'openid',
        'avatar',
        'nickname',
    ];

    public function user()
    {
        return $this->morphTo();
    }
}
