<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/4
 * Time: 10:20 AM
 */

namespace App\Supports;


class TrashedStatus
{
    const DEFAULT = '';
    const WITH = 'with';
    const ONLY = 'only';

    public static function fromBoolean($withTrashed)
    {
        return $withTrashed ? self::WITH : self::DEFAULT;
    }
}