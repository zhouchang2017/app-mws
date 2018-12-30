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
// 分配库存页面
Route::get('/pre-inventory-actions/{pre_inventory_action}/assign', 'PreInventoryActionController@assign')
    ->name('pre-inventory-actions.assign.create');
// 提交分配库存
Route::post('/pre-inventory-actions/{pre_inventory_action}/assigned', 'PreInventoryActionController@assigned')
    ->name('pre-inventory-actions.assign.store');

Route::resource('/pre-inventory-action-orders', 'PreInventoryActionOrderController');
// 变体
Route::resource('/product-variants', 'ProductVariantController');

Route::resource('warehouses', 'WarehouseController');
