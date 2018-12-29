<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/12/27
 * Time: 下午9:49
 */

Route::get('/home', 'HomeController@index')->name('home');

// 供货计划
Route::resource('/supplies', 'SupplyController');
// 供货计划审核
Route::patch('/supplies/{supply}/approved', 'SupplyController@approved')
    ->name('supplies.approved');

// 预出\入库(入库单\出货单)
Route::resource('/pre-inventory-actions', 'PreInventoryActionController');
// 预出\入库(入库单\出货单)审核
Route::patch('/pre-inventory-actions/{pre_inventory_action}/approved', 'PreInventoryActionController@approved')
    ->name('pre-inventory-actions.approved');
Route::get('/pre-inventory-actions/{pre_inventory_action}/assign', 'PreInventoryActionController@assign')
    ->name('pre-inventory-actions.assign');
// 变体
Route::resource('/product-variants', 'ProductVariantController');

Route::resource('warehouses', 'WarehouseController');
