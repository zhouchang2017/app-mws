<?php

namespace App\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use App\Models\PreInventoryActionOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class PreInventoryActionOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the pre inventory action order.
     *
     * @param Authorizable $user
     * @param  \App\Models\PreInventoryActionOrder $preInventoryActionOrder
     * @return mixed
     */
    public function view(Authorizable $user, PreInventoryActionOrder $preInventoryActionOrder)
    {
        return true;
    }

    /**
     * Determine whether the user can create pre inventory action orders.
     *
     * @param Authorizable $user
     * @return mixed
     */
    public function create(Authorizable $user)
    {
        //
    }

    /**
     * Determine whether the user can update the pre inventory action order.
     *
     * @param Authorizable $user
     * @param  \App\Models\PreInventoryActionOrder $preInventoryActionOrder
     * @return mixed
     */
    public function update(Authorizable $user, PreInventoryActionOrder $preInventoryActionOrder)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the pre inventory action order.
     *
     * @param Authorizable $user
     * @param  \App\Models\PreInventoryActionOrder $preInventoryActionOrder
     * @return mixed
     */
    public function delete(Authorizable $user, PreInventoryActionOrder $preInventoryActionOrder)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the pre inventory action order.
     *
     * @param Authorizable $user
     * @param  \App\Models\PreInventoryActionOrder $preInventoryActionOrder
     * @return mixed
     */
    public function restore(Authorizable $user, PreInventoryActionOrder $preInventoryActionOrder)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the pre inventory action order.
     *
     * @param Authorizable $user
     * @param  \App\Models\PreInventoryActionOrder $preInventoryActionOrder
     * @return mixed
     */
    public function forceDelete(Authorizable $user, PreInventoryActionOrder $preInventoryActionOrder)
    {
        //
    }
}
