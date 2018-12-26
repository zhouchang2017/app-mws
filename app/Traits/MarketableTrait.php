<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/11/1
 * Time: 下午11:05
 */

namespace Chang\Erp\Traits;


use Chang\Erp\Models\Market;

trait MarketableTrait
{
    public function market()
    {
        return $this->morphOne(Market::class, 'marketable');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function register()
    {
        if (is_null($this->market)) {
            $this->market()->create(['name' => $this->getName()]);
        }
        return $this->market;
    }

    public function sync()
    {
        return $this->register();
    }
}