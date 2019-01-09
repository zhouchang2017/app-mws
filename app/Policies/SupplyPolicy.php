<?php

namespace App\Policies;

use App\Models\SupplierUser;
use Illuminate\Contracts\Auth\Access\Authorizable;
use App\Models\Supply;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the supply.
     *
     * @param Authorizable $user
     * @param  \App\Models\Supply $supply
     * @return mixed
     */
    public function view(Authorizable $user, Supply $supply)
    {
        return true;
    }

    /**
     * Determine whether the user can create supplies.
     *
     * @param Authorizable $user
     * @return mixed
     */
    public function create(Authorizable $user)
    {
        //
    }

    /**
     * Determine whether the user can update the supply.
     *
     * @param Authorizable $user
     * @param  \App\Models\Supply $supply
     * @return mixed
     */
    public function update(Authorizable $user, Supply $supply)
    {
        if ($user instanceof SupplierUser && $supply->status !== Supply::UN_COMMIT) {
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can delete the supply.
     *
     * @param Authorizable $user
     * @param  \App\Models\Supply $supply
     * @return mixed
     */
    public function delete(Authorizable $user, Supply $supply)
    {
        //
    }

    /**
     * Determine whether the user can restore the supply.
     *
     * @param Authorizable $user
     * @param  \App\Models\Supply $supply
     * @return mixed
     */
    public function restore(Authorizable $user, Supply $supply)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the supply.
     *
     * @param Authorizable $user
     * @param  \App\Models\Supply $supply
     * @return mixed
     */
    public function forceDelete(Authorizable $user, Supply $supply)
    {
        //
    }

    /**
     * 供应商供货发货权限
     *
     * @param Authorizable $user
     * @param  \App\Models\Supply $supply
     * @return mixed
     */
    public function shipment(Authorizable $user, Supply $supply)
    {
        if ($supply->status === Supply::UN_SHIP) {
            return true;
        }
        return false;
    }

    /**
     * 供应商供货发货权限
     *
     * @param Authorizable $user
     * @param  \App\Models\Supply $supply
     * @return mixed
     */
    public function updateShipment(Authorizable $user, Supply $supply)
    {
        if ($supply->status === Supply::COMPLETED) {
            return false;
        }
        return true;
    }
}
