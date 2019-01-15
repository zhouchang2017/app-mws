<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/2
 * Time: 6:23 PM
 */

namespace App\Contracts;


interface Orderable
{
    public function getExpendItems();

    public function getDescriptionAttribute();

    public function getStatusAttribute();

    public function getTotalPriceAttribute();

    public function localOrder();
}