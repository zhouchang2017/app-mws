<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/11/1
 * Time: 下午11:05
 */

namespace App\Traits;


use App\Models\Market;

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

    public function sync()
    {
        return $this->market()->updateOrCreate([
            'marketable_id' => $this->id,
            'marketable_type' => get_class($this),
            'name' => $this->getName(),
        ]);
    }
}