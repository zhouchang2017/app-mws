<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/19
 * Time: 上午10:55
 */

namespace App\Traits;


use App\Models\Wechat;

trait WechatableTrait
{
    public function wechat()
    {
        return $this->morphOne(Wechat::class, 'wechatable');
    }

    public function hasBind()
    {
        return $this->wechat()->count() > 0;
    }
}