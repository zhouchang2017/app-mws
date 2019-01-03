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
    $order = \App\Models\DP\Order::find(115);
    return $order->getExpendItems();
//    $order->loadMissing(['units']);
//    $unitPrice = $order->unit_price;
//    $order->units->map(function ($item) use ($unitPrice) {
//        $item->price = $unitPrice + $item->adjustments_total;
//        return $item;
//    });
//    return $order;
});
//Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/users/{user}', 'UserController@show')->name('user.show');

//Route::get('/users/profile', 'UserController@profile')->name('user.profile');

