<?php

namespace App\Http\Requests;

use App\Models\SupplierUser;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ErpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function getSubDomain()
    {
        return array_first(explode('.', $this->getHost()));
    }

    public function userType()
    {
        return get_class($this->user());
    }

    public function isAdmin()
    {
        return $this->user() instanceof User;
    }

    public function isSupplier()
    {
        return $this->user() instanceof SupplierUser;
    }

    public function viaRelationship()
    {
        return $this->viaRelationName && $this->viaRelationId;
    }
}
