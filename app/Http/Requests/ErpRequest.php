<?php

namespace App\Http\Requests;

use App\Erp\Erp;
use App\Models\SupplierUser;
use App\Models\User;
use App\Resources\Resource;
use Illuminate\Foundation\Http\FormRequest;

class ErpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function getSubDomain()
    {
        return array_first(explode('.', $this->getHost()));
    }

    public function userType()
    {
        return get_class($this->user());
    }

    public function isAdmin()
    {
        return $this->user() instanceof User;
    }

    public function isSupplier()
    {
        return $this->user() instanceof SupplierUser;
    }


    /**
     * Get the class name of the resource being requested.
     *
     * @return mixed
     */
    public function resource()
    {
        return tap(Erp::resourceForKey($this->resource), function ($resource) {
            abort_if(is_null($resource), 404);
            abort_if(! $resource::authorizedToViewAny($this), 403);
        });
    }

    /**
     * Get a new instance of the resource being requested.
     *
     * @return \Laravel\Nova\Resource
     */
    public function newResource()
    {
        $resource = $this->resource();

        return new $resource($this->model());
    }

    /**
     * Find the resource model instance for the request.
     *
     * @param  mixed|null  $resourceId
     * @return Resource
     */
    public function findResourceOrFail($resourceId = null)
    {
        return $this->newResourceWith($this->findModelOrFail($resourceId));
    }

    /**
     * Find the model instance for the request.
     *
     * @param  mixed|null  $resourceId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findModelOrFail($resourceId = null)
    {
        if ($resourceId) {
            return $this->findModelQuery($resourceId)->firstOrFail();
        }

        return once(function () {
            return $this->findModelQuery()->firstOrFail();
        });
    }

    /**
     * Get the query to find the model instance for the request.
     *
     * @param  mixed|null  $resourceId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findModelQuery($resourceId = null)
    {
        return $this->newQueryWithoutScopes()->whereKey(
            $resourceId ?? $this->resourceId
        );
    }

    /**
     * Get a new instance of the resource being requested.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return Resource
     */
    public function newResourceWith($model)
    {
        $resource = $this->resource();

        return new $resource($model);
    }

    /**
     * Get a new query builder for the underlying model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        return $this->model()->newQuery();
    }

    /**
     * Get a new, scopeless query builder for the underlying model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQueryWithoutScopes()
    {
        return $this->model()->newQueryWithoutScopes();
    }

    /**
     * Get a new instance of the underlying model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model()
    {
        $resource = $this->resource();

        return $resource::newModel();
    }

    /**
     * Find the parent resource model instance for the request.
     *
     * @return \Laravel\Nova\Resource
     */
    public function findParentResourceOrFail()
    {
        return once(function () {
            $resource = $this->viaResource();

            return new $resource($this->findParentModelOrFail());
        });
    }

    /**
     * Find the parent resource model instance for the request.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findParentModel()
    {
        return once(function () {
            if (! $this->viaRelationship()) {
                return;
            }

            return Erp::modelInstanceForKey($this->viaResource)
                ->newQueryWithoutScopes()
                ->find($this->viaResourceId);
        });
    }

    /**
     * Find the parent resource model instance for the request or abort.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findParentModelOrFail()
    {
        return $this->findParentModel() ?: abort(404);
    }

    /**
     * Get the displayable pivot model name for a "via relationship" request.
     *
     * @return string
     */
    public function pivotName()
    {
        if (! $this->viaRelationship()) {
            return Resource::DEFAULT_PIVOT_NAME;
        }

        $resource = Erp::resourceInstanceForKey($this->viaResource);

        return ($parent = $this->findParentModel())
            ? class_basename($parent->{$this->viaRelationship}()->getPivotClass())
            : Resource::DEFAULT_PIVOT_NAME;
    }

    /**
     * Get a new instance of hte "via" resource being requested.
     *
     * @return Resource
     */
    public function newViaResource()
    {
        $resource = $this->viaResource();

        return new $resource($resource::newModel());
    }

    /**
     * Get the class name of the "via" resource being requested.
     *
     * @return string
     */
    public function viaResource()
    {
        return Erp::resourceForKey($this->viaResource);
    }

    /**
     * Determine if the request is via a relationship.
     *
     * @return bool
     */
    public function viaRelationship()
    {
        return $this->viaResource && $this->viaResourceId && $this->viaRelationship;
    }
}
