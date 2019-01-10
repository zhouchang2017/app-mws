<?php

namespace App\Policies;

use App\Models\SupplierUser;
use App\Models\User;
use Illuminate\Contracts\Auth\Access\Authorizable;
use App\Models\Withdraw;
use Illuminate\Auth\Access\HandlesAuthorization;

class WithdrawPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the withdraw.
     *
     * @param Authorizable $user
     * @param  \App\Models\Withdraw $withdraw
     * @return mixed
     */
    public function view(Authorizable $user, Withdraw $withdraw)
    {
        return true;
    }

    /**
     * Determine whether the user can create withdraws.
     *
     * @param Authorizable $user
     * @return mixed
     */
    public function create(Authorizable $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the withdraw.
     *
     * @param Authorizable $user
     * @param  \App\Models\Withdraw $withdraw
     * @return mixed
     */
    public function update(Authorizable $user, Withdraw $withdraw)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the withdraw.
     *
     * @param Authorizable $user
     * @param  \App\Models\Withdraw $withdraw
     * @return mixed
     */
    public function delete(Authorizable $user, Withdraw $withdraw)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the withdraw.
     *
     * @param Authorizable $user
     * @param  \App\Models\Withdraw $withdraw
     * @return mixed
     */
    public function restore(Authorizable $user, Withdraw $withdraw)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the withdraw.
     *
     * @param Authorizable $user
     * @param  \App\Models\Withdraw $withdraw
     * @return mixed
     */
    public function forceDelete(Authorizable $user, Withdraw $withdraw)
    {
        return true;
    }

    /*
     * 供应商提交退库申请
     * */
    public function submit(Authorizable $user, Withdraw $withdraw)
    {
        if ($user instanceof SupplierUser && $withdraw->status === Withdraw::UN_COMMIT) {
            return true;
        }
        return false;
    }

    /*
     * 管理员审核退库申请
     * */
    public function approve(Authorizable $user, Withdraw $withdraw)
    {
        if ($user instanceof User && $withdraw->status === Withdraw::PENDING) {
            return true;
        }
        return false;
    }

    /*
     * 完成退库操作
     * */
    public function completed(Authorizable $user, Withdraw $withdraw)
    {
        if ($user instanceof User && $withdraw->status === Withdraw::SHIPPED) {
            return true;
        }
        return false;
    }
}
