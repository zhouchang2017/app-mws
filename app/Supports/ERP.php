<?php


namespace App\Supports;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ERP
{
    /**
     * The registered resource names.
     *
     * @var array
     */
    public static $resources = [];

    /**
     * The variables that should be made available on the Nova JavaScript object.
     *
     * @var array
     */
    public static $jsonVariables = [];


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

    /**
     * Get the JSON variables that should be provided to the global Nova JavaScript object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function jsonVariables(Request $request)
    {
        return collect(static::$jsonVariables)->map(function ($variable) use ($request) {
            return is_callable($variable) ? $variable($request) : $variable;
        })->all();
    }

    /**
     * Provide additional variables to the global Nova JavaScript object.
     *
     * @param  array  $variables
     * @return static
     */
    public static function provideToScript(array $variables)
    {
        if (empty(static::$jsonVariables)) {
            static::$jsonVariables = [
                'userId' => Auth::id() ?? null,
                'userType' => get_class(Auth::user())
            ];
        }

        static::$jsonVariables = array_merge(static::$jsonVariables, $variables);

        return new static;
    }
}