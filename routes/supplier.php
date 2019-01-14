<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/12/27
 * Time: 下午9:31
 */
Route::get('/home', 'HomeController@index')->name('home');

// 供货计划
Route::resource('/supplies', 'SupplyController');
// 供货计划提交审核
Route::patch('/supplies/{supply}/submit', 'SupplyController@submit')->name('supplies.submit');
// 供货计划操作单发货页面
Route::get('/supplies/{supply}/shipment/{order}', 'SupplyController@shipment')->name('supplies.order.shipment.create');

// 供货计划操作单发货提交
Route::post('/supplies/{supply}/shipment/{order}', 'SupplyController@shipped')->name('supplies.order.shipment.store');

// 检测code唯一
Route::get('/product-variants/check/code/{code}', 'ProductVariantController@checkCode')
    ->name('product-variants.check.code');
// 变体
Route::resource('/product-variants', 'ProductVariantController');

Route::resource('/products', 'ProductController');

// 产品提交审核
Route::patch('/products/{product}/submit', 'ProductController@submit')->name('products.submit');


// 产品包含的销售属性
Route::get('/products/{product}/options', 'ProductController@options')->name('products.options');

// 产品分类
Route::get('/taxons', 'TaxonController@index')->name('taxons.index');

// 产品属性
Route::get('/product-attributes', 'ProductAttributeController@index')->name('product-attributes.index');

// 产品销售属性
Route::get('/product-options', 'ProductOptionController@index')->name('product-options.index');

// 消息通知
Route::get('/notifications', 'HomeController@notifications')->name('notifications.index');
// 标记已读
Route::patch('/notifications/{id}', 'HomeController@notificationMakeAsRead')->name('notifications.read');

// 促销计划
Route::get('/promotion-plans', 'PromotionPlanController@index')->name('promotion-plans.index');
Route::get('/promotion-plans/{promotionPlan}', 'PromotionPlanController@show')->name('promotion-plans.show');
// 供应商同意促销计划邀请
Route::patch('/promotion-plans/{promotionPlan}/confirm',
    'PromotionPlanController@confirm')->name('promotion-plans.confirm');

// 供应商退仓
Route::resource('/withdraws', 'WithdrawController');
// 提交退仓
Route::patch('/withdraws/{withdraw}/submit', 'WithdrawController@submit')->name('withdraws.submit');

// 仓库api
Route::get('/warehouses', 'WarehouseController@index')->name('warehouses.index');
// 库存api
Route::get('/inventories', 'InventoryController@index')->name('inventories.index');

// 微信二维码url
Route::get('/wechat/bind/create', 'Supplier\Auth\WechatController@getBindUrl')->name('wechat.bind.create');

// 个人中心
Route::get('/profile', 'UserController@profile')->name('users.profile');

// 供应商保存地址
Route::post('/suppliers/{supplier}/address', 'SupplierController@address')->name('suppliers.address.store');
Route::patch('/suppliers/{supplier}/address', 'SupplierController@address')->name('suppliers.address.store');

// 供应商详情
Route::get('/suppliers/profile', 'SupplierController@profile')->name('suppliers.profile');


// 轮询是否绑定成功
Route::get('/wechat/bind','Supplier\Auth\WechatController@checkIsBind');

// 图片上传
Route::post('/fs/upload/image', 'FileSystemController@image')->name('upload.image.store');
Route::delete('/fs/upload/image', 'FileSystemController@image')->name('upload.image.destroy');