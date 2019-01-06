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