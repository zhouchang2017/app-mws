<?php

namespace App\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable as User;
use App\Models\Supplier;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the supplier.
     *
     * @param User $user
     * @param  \App\Models\Supplier $supplier
     * @return mixed
     */
    public function view(User $user, Supplier $supplier)
    {
        if($supplier->users->contains($user)){
            return true;
        }
        if($user instanceof \App\Models\User){
            return true;
        }
    }

    /**
     * Determine whether the user can create suppliers.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user instanceof \App\Models\User){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the supplier.
     *
     * @param User $user
     * @param  \App\Models\Supplier $supplier
     * @return mixed
     */
    public function update(User $user, Supplier $supplier)
    {
        if($user instanceof \App\Models\User){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the supplier.
     *
     * @param User $user
     * @param  \App\Models\Supplier $supplier
     * @return mixed
     */
    public function delete(User $user, Supplier $supplier)
    {
        if($user instanceof \App\Models\User){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the supplier.
     *
     * @param User $user
     * @param  \App\Models\Supplier $supplier
     * @return mixed
     */
    public function restore(User $user, Supplier $supplier)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the supplier.
     *
     * @param User $user
     * @param  \App\Models\Supplier $supplier
     * @return mixed
     */
    public function forceDelete(User $user, Supplier $supplier)
    {
        //
    }

    public function addUser(User $user, Supplier $supplier)
    {
        if ($supplier->users()->count() === 5) {
            return false;
        }

        if ($user === $supplier->manager) {
            return true;
        }

        if (erpRequest()->isAdmin()) {
            return true;
        }
    }
}
