<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/29
 * Time: 4:29 PM
 */

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class NotificationWhereReadScope implements Scope
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
        $builder->when(request()->has('is_read'), function ($query) {
            switch (request()->get('is_read')) {
                case 0:
                    // 未读消息
                    $query->whereNull('read_at');

                    break;
                default:
                    // 已读消息
                    $query->whereNotNull('read_at');
            }
        });
    }
}