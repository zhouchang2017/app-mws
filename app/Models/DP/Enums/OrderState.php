<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/2
 * Time: 5:01 PM
 */

namespace App\Models\DP\Enums;


interface OrderState
{
    const CHECKOUT = 'checkout';
    const NEW = 'new';
    const CANCELLED = 'cancelled';
    const FULFILLED = 'fulfilled';
}