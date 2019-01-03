<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2019/1/4
 * Time: 上午12:24
 */

namespace App\Traits;


use Illuminate\Support\Str;

trait HttpResource
{
    public function getUriKey()
    {
        return static::uriKey();
    }

    public function getLabel()
    {
        return static::label();
    }

    public function getSingularLabel()
    {
        return static::singularLabel();
    }


    public function canSearch()
    {
        return true;
    }

    public function canCreate()
    {
        return true;
    }

    public function canUpdate()
    {
        return true;
    }

    public function canDestroy()
    {
        return true;
    }

    public function canView()
    {
        return true;
    }

    /**
     * Get the displayable label of the controller.
     *
     * @return string
     */
    public static function label()
    {
        return Str::plural(static::resourceName());
    }

    /**
     * Get the displayable singular label of the controller.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return Str::singular(static::label());
    }

    /**
     * Get the URI key for the controller.
     *
     * @return string
     */
    public static function uriKey()
    {
        return Str::kebab(Str::plural(static::resourceName()));
    }

    /**
     * Get the Resource name for the controller.
     *
     * @return string
     */
    public static function resourceName()
    {
        return Str::before(class_basename(static::class), 'Controller');
    }

}