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

Route::view('/', 'home');

Route::get('/test', function (\App\Http\Requests\ErpRequest $request) {
    return \App\Models\DP\Taxon::all();
//    return \App\Models\Inventory::where('warehouse_id',10)->get();
});

Route::get('/excels/import', 'HelperImportExcelController@import');

