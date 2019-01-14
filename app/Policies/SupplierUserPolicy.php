<?php

namespace App\Policies;

use App\Models\Supplier;
use Illuminate\Contracts\Auth\Access\Authorizable as User;
use App\Models\SupplierUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierUserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the supplier user.
     *
     * @param User $user
     * @param  \App\Models\SupplierUser $supplierUser
     * @return mixed
     */
    public function view(User $user, SupplierUser $supplierUser)
    {
        if ($user instanceof SupplierUser) {
            /** @var Supplier $supplier */
            $supplier = $user->supplier;
            return $supplier->users->contains($supplierUser);
        }
        return true;
    }

    /**
     * Determine whether the user can create supplier users.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user instanceof SupplierUser) {
            /** @var Supplier $supplier */
            $supplier = $user->supplier;
            return $supplier->manager === $user;
        }
        return true;
    }

    /**
     * Determine whether the user can update the supplier user.
     *
     * @param User $user
     * @param  \App\Models\SupplierUser $supplierUser
     * @return mixed
     */
    public function update(User $user, SupplierUser $supplierUser)
    {
        if ($user === $supplierUser) {
            return true;
        }
        if ($user instanceof SupplierUser) {
            /** @var Supplier $supplier */
            $supplier = $user->supplier;
            return $supplier->manager === $user;
        }
        return true;
    }

    /**
     * Determine whether the user can delete the supplier user.
     *
     * @param User $user
     * @param  \App\Models\SupplierUser $supplierUser
     * @return mixed
     */
    public function delete(User $user, SupplierUser $supplierUser)
    {
        if ($user instanceof SupplierUser) {
            /** @var Supplier $supplier */
            $supplier = $user->supplier;
            return $supplier->manager === $user;
        }
        return true;
    }

    /**
     * Determine whether the user can restore the supplier user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\SupplierUser $supplierUser
     * @return mixed
     */
    public function restore(User $user, SupplierUser $supplierUser)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the supplier user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\SupplierUser $supplierUser
     * @return mixed
     */
    public function forceDelete(User $user, SupplierUser $supplierUser)
    {
        //
    }
}
