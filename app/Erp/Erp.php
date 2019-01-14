<?php

namespace App\Erp;


use App\Http\Requests\ErpRequest;
use App\Resources\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class Erp
{
    public static $resources = [];

    public static $resourcesByModel = [];

    public static $cards = [];

    /**
     * Get meta data information about all resources for client side consumption.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function resourceInformation(Request $request)
    {
        return collect(static::$resources)->map(function ($resource) use ($request) {
            return [
                'uriKey' => $resource::uriKey(),
                'label' => $resource::label(),
                'singularLabel' => $resource::singularLabel(),
//                'authorizedToCreate' => $resource::authorizedToCreate($request),
                'searchable' => $resource::searchable(),
            ];
        })->values()->all();
    }

    /**
     * Get the available dashboard cards for the given request.
     *
     * @param  ErpRequest  $request
     * @return \Illuminate\Support\Collection
     */
    public static function availableDashboardCards(ErpRequest $request)
    {
        return collect(static::$cards)->filter->authorize($request)->values();
    }

    /**
     * Register new dashboard cards with Nova.
     *
     * @param  array  $cards
     * @return static
     */
    public static function cards(array $cards)
    {
        static::$cards = array_merge(
            static::$cards,
            $cards
        );

        return new static;
    }

    /**
     * Get the resources available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function availableResources(Request $request)
    {
        return collect(static::$resources)->filter(function ($resource) use ($request) {
            return $resource::authorizedToViewAny($request) &&
                $resource::availableForNavigation($request);
        })->all();
    }

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
     * Register all of the resource classes in the given directory.
     *
     * @param  string $directory
     * @return void
     * @throws \ReflectionException
     */
    public static function resourcesIn($directory)
    {
        $namespace = app()->getNamespace();

        $resources = [];

        foreach ((new Finder())->in($directory)->files() as $resource) {
            $resource = $namespace.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($resource->getPathname(), app_path().DIRECTORY_SEPARATOR)
                );

            if (is_subclass_of($resource, Resource::class) &&
                ! (new \ReflectionClass($resource))->isAbstract()) {
                $resources[] = $resource;
            }
        }

        static::resources(
            collect($resources)->sort()->all()
        );
    }

    /**
     * Get the resource class name for a given key.
     *
     * @param  string  $key
     * @return string
     */
    public static function resourceForKey($key)
    {
        return collect(static::$resources)->first(function ($value) use ($key) {
            return $value::uriKey() === $key;
        });
    }

    /**
     * Get a new resource instance with the given model instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Laravel\Nova\Resource
     */
    public static function newResourceFromModel($model)
    {
        $resource = static::resourceForModel($model);

        return new $resource($model);
    }

    /**
     * Get the resource class name for a given model class.
     *
     * @param  object|string  $class
     * @return string
     */
    public static function resourceForModel($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        if (isset(static::$resourcesByModel[$class])) {
            return static::$resourcesByModel[$class];
        }

        $resource = collect(static::$resources)->first(function ($value) use ($class) {
            return $value::$model === $class;
        });

        return static::$resourcesByModel[$class] = $resource;
    }

    /**
     * Get a resource instance for a given key.
     *
     * @param  string  $key
     * @return \Laravel\Nova\Resource|null
     */
    public static function resourceInstanceForKey($key)
    {
        if ($resource = static::resourceForKey($key)) {
            return new $resource($resource::newModel());
        }
    }

    /**
     * Get a fresh model instance for the resource with the given key.
     *
     * @param  string  $key
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function modelInstanceForKey($key)
    {
        $resource = static::resourceForKey($key);

        return $resource ? $resource::newModel() : null;
    }
}