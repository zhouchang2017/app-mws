<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'welcome');

Route::get('/test',function (){

    return \App\Models\Inventory::where('warehouse_id',10)->get();
});
//Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/users/{user}', 'UserController@show')->name('user.show');

//Route::get('/users/profile', 'UserController@profile')->name('user.profile');

