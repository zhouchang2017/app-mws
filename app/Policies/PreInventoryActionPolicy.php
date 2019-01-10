<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Contracts\Auth\Access\Authorizable;
use App\Models\PreInventoryAction;
use Illuminate\Auth\Access\HandlesAuthorization;

class PreInventoryActionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the pre inventory action.
     *
     * @param Authorizable $user
     * @param  \App\Models\PreInventoryAction $preInventoryAction
     * @return mixed
     */
    public function view(Authorizable $user, PreInventoryAction $preInventoryAction)
    {
        return true;
    }

    /**
     * Determine whether the user can create pre inventory actions.
     *
     * @param Authorizable $user
     * @return mixed
     */
    public function create(Authorizable $user)
    {
        //
    }

    /**
     * Determine whether the user can update the pre inventory action.
     *
     * @param Authorizable $user
     * @param  \App\Models\PreInventoryAction $preInventoryAction
     * @return mixed
     */
    public function update(Authorizable $user, PreInventoryAction $preInventoryAction)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the pre inventory action.
     *
     * @param Authorizable $user
     * @param  \App\Models\PreInventoryAction $preInventoryAction
     * @return mixed
     */
    public function delete(Authorizable $user, PreInventoryAction $preInventoryAction)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the pre inventory action.
     *
     * @param Authorizable $user
     * @param  \App\Models\PreInventoryAction $preInventoryAction
     * @return mixed
     */
    public function restore(Authorizable $user, PreInventoryAction $preInventoryAction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the pre inventory action.
     *
     * @param Authorizable $user
     * @param  \App\Models\PreInventoryAction $preInventoryAction
     * @return mixed
     */
    public function forceDelete(Authorizable $user, PreInventoryAction $preInventoryAction)
    {
        //
    }

    /**
     * 审核预出库\预入库单
     *
     * @param Authorizable $user
     * @param  \App\Models\PreInventoryAction $preInventoryAction
     * @return boolean
     */
    public function approve(Authorizable $user, PreInventoryAction $preInventoryAction)
    {

        if ($preInventoryAction->status === PreInventoryAction::PENDING && $user instanceof User) {
            return true;
        }
        return false;
    }

    /**
     * 为预出库\预入库单分配仓库权限
     *
     * @param Authorizable $user
     * @param  \App\Models\PreInventoryAction $preInventoryAction
     * @return boolean
     */
    public function assign(Authorizable $user, PreInventoryAction $preInventoryAction)
    {
        if ($preInventoryAction->status === PreInventoryAction::APPROVED && $user instanceof User) {
            return true;
        }
        return false;
    }
}
