<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/4
 * Time: 10:19 AM
 */

namespace App\Supports;


class ApplySoftDeleteConstraint
{
    /**
     * Apply the trashed state constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $withTrashed
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function __invoke($query, $withTrashed)
    {
        if ($withTrashed == TrashedStatus::WITH) {
            $query = $query->withTrashed();
        } elseif ($withTrashed == TrashedStatus::ONLY) {
            $query = $query->onlyTrashed();
        }

        return $query;
    }
}