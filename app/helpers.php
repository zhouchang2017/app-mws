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


if ( !function_exists('subDomain')) {
    function subDomain()
    {
        return array_first(explode('.', request()->getHost()));
    }
}


if ( !function_exists('adminComing')) {
    function adminComing()
    {
        return subDomain() === 'admin';
    }
}
if ( !function_exists('supplierComing')) {
    function supplierComing()
    {
        return subDomain() === 'supplier';
    }
}