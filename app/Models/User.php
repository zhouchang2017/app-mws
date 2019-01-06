<?php

namespace App\Models;

use App\Notifications\SupplyPendingNotification;
use App\Traits\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function scopeSupplyPendingNotify($query)
    {
        return $this->unreadNotifications()->whereType(SupplyPendingNotification::class);
    }
}
