<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/9
 * Time: 10:29 AM
 */

if ( !function_exists('isAdmin')) {
    function isAdmin()
    {
        return auth()->user() instanceof \App\Models\User;
    }
}

if ( !function_exists('isSupplier')) {
    function isSupplier()
    {
        return auth()->user() instanceof \App\Models\SupplierUser;
    }
}

if ( !function_exists('erpRequest')) {
    function erpRequest()
    {
        return app(\App\Http\Requests\ErpRequest::class);
    }
}


