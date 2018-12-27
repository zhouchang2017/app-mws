<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/12/27
 * Time: 下午9:25
 */
return [
    'supplier' => [
        'router' => [ 'domain'     => env('SUPPLIER_DOMAIN', 'supplier') . '.' . config('app.url'),
                      'as'         => 'supplier.',
                      'namespace'  => 'App\Http\Controllers\Supplier',
//    'prefix' => 'supplier-api',
                      'middleware' => [ 'web', 'supplier', 'auth:supplier_web' ], ],
    ],

    'admin' => [
        'router' => [ 'domain'     => env('ADMIN_DOMAIN', 'admin') . '.' . config('app.url'),
                      'as'         => 'admin.',
                      'namespace'  => 'App\Http\Controllers\Admin',
//    'prefix' => 'admin-api',
                      'middleware' => [ 'web', 'admin', 'auth:admin' ], ],
    ],
];