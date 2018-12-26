<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/11/3
 * Time: 下午7:57
 */

namespace App\Contracts;


use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

interface Addressable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function addresses(): MorphMany;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function address(): MorphOne;
}