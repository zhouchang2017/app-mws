<?php

namespace App\Scopes;

use App\Models\SupplierUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SupplierProductScope implements Scope
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->user() instanceof SupplierUser) {
            $builder->whereIn('products.id', auth()->user()->supplier->product_ids);
        }
    }
}