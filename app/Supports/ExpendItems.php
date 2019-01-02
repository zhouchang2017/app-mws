<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/2
 * Time: 5:38 PM
 */

namespace App\Supports;


use Illuminate\Support\Collection;

class ExpendItems extends Collection
{
    public function __construct($items = [])
    {
        if ($items instanceof Collection) {
            $items = $items->reduce(function ($res, $item) {
                return tap($res, function ($res) use ($item) {
                    $res->push(new ExpendItem($item));
                });
            }, collect([]));
        }
        parent::__construct($items);
    }
}