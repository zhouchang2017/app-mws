<?php

namespace App\Traits;

use App\Models\DP\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Trait Authorizable
 * @package App\Traits
 */
trait Authorizable
{
    public function getAuthorizeAttribute()
    {
        return collect($this->allowAuthorizes)->concat($this->appendAuthorizes)->mapWithKeys(function($item){
            return [
                Str::camel('can_'.$item) => $this->authorizedTo(request(),$item)
            ];
        });
    }

    /**
     * Determine if the given resource is authorizable.
     *
     * @return bool
     */
    public static function authorizable()
    {
        return !is_null(Gate::getPolicyFor(get_class(new static())));
    }

    /**
     * Determine if the resource should be available for the given request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     * @throws \Throwable
     */
    public function authorizeToViewAny(Request $request)
    {
        if ( !static::authorizable()) {
            return;
        }
        if (method_exists(Gate::getPolicyFor($this), 'viewAny')) {
            $this->authorizeTo($request, 'viewAny');
        }
    }

    /**
     * Determine if the resource should be available for the given request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function authorizedToViewAny(Request $request)
    {
        if ( !static::authorizable()) {
            return true;
        }

        return method_exists(Gate::getPolicyFor(get_class(new static())), 'viewAny')
            ? Gate::check('viewAny', get_class(new static()))
            : true;
    }

    /**
     * Determine if the current user can view the given resource or throw an exception.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     *
     * @throws \Throwable
     */
    public function authorizeToView(Request $request)
    {
        return $this->authorizeTo($request, 'view') && $this->authorizeToViewAny($request);
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public function authorizedToView(Request $request)
    {
        return $this->authorizedTo($request, 'view') && $this->authorizedToViewAny($request);
    }

    /**
     * Determine if the current user can create new resources or throw an exception.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     *
     * @throws \Throwable
     */
    public static function authorizeToCreate(Request $request)
    {
        return static::authorizedToCreate($request);
    }

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        if (static::authorizable()) {
            return Gate::check('create', get_class(new static()));
        }

        return true;
    }

    /**
     * Determine if the current user can update the given resource or throw an exception.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Throwable
     */
    public function authorizeToUpdate(Request $request)
    {
        return $this->authorizeTo($request, 'update');
    }

    /**
     * Determine if the current user can update the given resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return $this->authorizedTo($request, 'update');
    }

    /**
     * Determine if the current user can delete the given resource or throw an exception.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Throwable
     */
    public function authorizeToDelete(Request $request)
    {
        return $this->authorizeTo($request, 'delete');
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return $this->authorizedTo($request, 'delete');
    }

    /**
     * Determine if the current user can restore the given resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public function authorizedToRestore(Request $request)
    {
        return $this->authorizedTo($request, 'restore');
    }

    /**
     * Determine if the current user can force delete the given resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public function authorizedToForceDelete(Request $request)
    {
        return $this->authorizedTo($request, 'forceDelete');
    }

    /**
     * Determine if the user can add / associate models of the given type to the resource.
     *
     * @param Request $request
     * @param  \Illuminate\Database\Eloquent\Model|string $model
     * @return bool
     */
    public function authorizedToAdd(Request $request, $model)
    {
        if ( !static::authorizable()) {
            return true;
        }

        $method = 'add' . class_basename($model);

        return method_exists(Gate::getPolicyFor($this->model()), $method)
            ? Gate::check($method, $this->model())
            : true;
    }

    /**
     * Determine if the user can attach any models of the given type to the resource.
     *
     * @param Request $request
     * @param  \Illuminate\Database\Eloquent\Model|string $model
     * @return bool
     */
    public function authorizedToAttachAny(Request $request, $model)
    {
        if ( !static::authorizable()) {
            return true;
        }

        $method = 'attachAny' . Str::singular(class_basename($model));

        return method_exists(Gate::getPolicyFor($this->model()), $method)
            ? Gate::check($method, [ $this->model() ])
            : true;
    }

    /**
     * Determine if the user can attach models of the given type to the resource.
     *
     * @param Request $request
     * @param  \Illuminate\Database\Eloquent\Model|string $model
     * @return bool
     */
    public function authorizedToAttach(Request $request, $model)
    {
        if ( !static::authorizable()) {
            return true;
        }

        $method = 'attach' . Str::singular(class_basename($model));

        return method_exists(Gate::getPolicyFor($this->model()), $method)
            ? Gate::check($method, [ $this->model(), $model ])
            : true;
    }

    /**
     * Determine if the user can detach models of the given type to the resource.
     *
     * @param Request $request
     * @param  \Illuminate\Database\Eloquent\Model|string $model
     * @param  string $relationship
     * @return bool
     */
    public function authorizedToDetach(Request $request, $model, $relationship)
    {
        if ( !static::authorizable()) {
            return true;
        }

        $method = 'detach' . Str::singular(class_basename($model));

        return method_exists(Gate::getPolicyFor($this->model()), $method)
            ? Gate::check($method, [ $this->model(), $model ])
            : true;
    }

    /**
     * Determine if the current user has a given ability.
     *
     * @param Request $request
     * @param  string $ability
     * @return bool
     *
     * @throws \Throwable
     */
    public function authorizeTo(Request $request, $ability)
    {
        return $this->authorizedTo($request, $ability);
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @param Request $request
     * @param  string $ability
     * @return bool
     */
    public function authorizedTo(Request $request, $ability)
    {
        return static::authorizable() ? Gate::check($ability, $this) : true;
    }
}
