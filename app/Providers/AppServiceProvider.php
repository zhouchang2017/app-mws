<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Request::macro('viaRelationship', function () {
            return $this->viaRelationName && $this->viaRelationId;
        });

        Request::macro('findRelationship', function ($model) {
            if ( !($this->viaRelationName && $this->viaRelationId)) {
                return null;
            }
            if (is_string($model)) {
                $model = app($model);
            }
            $relation = $model->{$this->viaRelationName}()->getModel();
            return $relation->find($this->viaRelationId);
        });
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
