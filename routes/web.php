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

// Route::get('/{any}', function () {
//     return view('welcome');
// });
// Route::get('/', function () {
//     return view('welcome');
// });
Route::resource('/category', 'CategoryController');
Route::resource('/property', 'PropertyController');
Route::resource('/inventory', 'InventoryController');
Route::resource('/', 'ViewInventoryController');
Route::get('/viewInventory', 'ViewInventoryController@create');
Route::get('/categories', 'CategoryController@create');
Route::get('/propeties', 'PropertyController@create');
Route::get('/inventories', 'InventoryController@create');
Route::get('/getInventoryById/{id}', 'InventoryController@getInventoryById');
Route::post('/category/update', 'CategoryController@update');
Route::post('/category/delete', 'CategoryController@destroy');
Route::post('/inventory/update', 'InventoryController@update');
Route::post('/inventory/delete', 'InventoryController@destroy');

Route::get('/{any}', function () {
    return view('welcome');
});


