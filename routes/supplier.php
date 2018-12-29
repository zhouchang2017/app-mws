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

Route::resource('/product-variants', 'ProductVariantController');
Route::resource('/products', 'ProductController');

