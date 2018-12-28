<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/12/27
 * Time: 下午9:31
 */
Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/supplies', 'SupplyController');

Route::resource('/pre-inventory-actions', 'PreInventoryActionController');

Route::resource('/product-variants', 'ProductVariantController');
Route::resource('/products', 'ProductController');

