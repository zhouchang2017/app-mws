<?php

namespace App\Models;


use App\Traits\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SupplierUser extends Authenticatable
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
        'phone',
        'supplier_id',
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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products()
    {
        return $this->supplier->products;
    }

    public function variants()
    {
        return $this->supplier->variants;
    }
}
