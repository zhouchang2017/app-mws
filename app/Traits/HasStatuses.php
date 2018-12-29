<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/29
 * Time: 4:26 PM
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Spatie\ModelStatus\HasStatuses as BaseHasStatuses;

trait HasStatuses
{
    use BaseHasStatuses;

    public function scopeWithStatus(Builder $builder)
    {
        $builder->with('state');
    }

    public function state()
    {
        return $this->morphOne($this->getStatusModelClassName(), 'model', 'model_type', $this->getModelKeyColumnName())
            ->latest('id');
    }
}