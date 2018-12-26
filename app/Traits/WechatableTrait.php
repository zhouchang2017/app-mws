<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/19
 * Time: 上午10:55
 */

namespace Chang\Erp\Traits;


use Chang\Erp\Models\Wechat;

trait WechatableTrait
{
    public function wechat()
    {
        return $this->morphOne(Wechat::class, 'wechatable');
    }
}