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
// 供货计划确认完成
Route::patch('/supplies/{supply}/completed', 'SupplyController@completed')
    ->name('supplies.completed');

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
// 操作单
Route::resource('/pre-inventory-action-orders', 'PreInventoryActionOrderController');
// 操作单出入库检测
Route::get('/pre-inventory-action-orders/{pre_inventory_action_order}/check', 'PreInventoryActionOrderController@check')
    ->name('pre-inventory-action-orders.check');

// 操作单发货页面
Route::get('/pre-inventory-action-orders/{pre_inventory_action_order}/shipment',
    'PreInventoryActionOrderController@shipment')
    ->name('pre-inventory-action-orders.shipment.create');

// 操作单发货提交
Route::post('/pre-inventory-action-orders/{pre_inventory_action_order}/shipment',
    'PreInventoryActionOrderController@shipped')
    ->name('pre-inventory-action-orders.shipment.store');

Route::post('/pre-inventory-action-order-items/{pre_inventory_action_order_item}/check',
    'PreInventoryActionOrderItemController@addCheck')
    ->name('pre-inventory-action-order-items.check.create');

Route::post('/pre-item-states/{id}',
    'PreInventoryActionOrderItemStateController@createAttachment')
    ->name('pre-item-states.attachment.create');

// 检测code唯一
Route::get('/product-variants/check/code/{code}', 'ProductVariantController@checkCode')
    ->name('product-variants.check.code');

// 变体
Route::resource('/product-variants', 'ProductVariantController');

// 仓库
Route::resource('warehouses', 'WarehouseController');

// 仓库类型
Route::resource('warehouse-types', 'WarehouseTypeController');

// 产品
Route::resource('/products', 'ProductController');
// 产品包含的销售属性
Route::get('/products/{product}/options', 'ProductController@options')->name('products.options');
// 产品分类
Route::get('/taxons', 'TaxonController@index')->name('taxons.index');

// 产品属性
Route::resource('/product-attributes', 'ProductAttributeController');

// 产品销售属性
Route::resource('/product-options', 'ProductOptionController');

// 库存
Route::get('/inventories/search', 'InventoryController@search')->name('inventories.search');
Route::resource('/inventories', 'InventoryController');

// 价格调整类型
Route::resource('/attachment-types', 'AttachmentTypeController');

// 消息通知
Route::get('/notifications', 'HomeController@notifications')->name('notifications.index');
// 标记已读
Route::patch('/notifications/{id}', 'HomeController@notificationMakeAsRead')->name('notifications.read');

Route::get('/users', 'UserController@index')->name('users.index');

// 地址
Route::get('/addresses/create', 'AddressController@create')->name('addresses.create');

// 中华人民共和国行政区划（五级）：省级、地级、县级、乡级和村级

Route::get('/divisions/provinces', 'DivisionController@provinces')->name('divisions.provinces.search');
Route::get('/divisions/cities', 'DivisionController@cities')->name('divisions.cities.search');
Route::get('/divisions/areas', 'DivisionController@areas')->name('divisions.areas.search');
