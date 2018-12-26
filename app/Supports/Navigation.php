<?php


namespace App\Supports;


class Navigation
{
    /**
     * The registered resource names.
     *
     * @var array
     */
    public static $resources = [];

    /**
     * Register the given resources.
     *
     * @param  array  $resources
     * @return static
     */
    public static function resources(array $resources)
    {
        static::$resources = array_merge(static::$resources, $resources);

        return new static;
    }
}