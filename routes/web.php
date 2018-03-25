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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/processOrder', 'OrderController@processOrder')->name('processOrder');
	Route::get('/checkout/{product_id}/{product_quantity}', 'OrderController@index')->name('checkout');
	Route::get('/checkPromotionCode', 'OrderController@checkPromotionCode')->name('checkPromotionCode');
	Route::get('/totalPrice', 'OrderController@totalPrice')->name('totalPrice');
	Route::get('/completeOrder', 'OrderController@completeOrder')->name('completeOrder');
	Route::get('/orderSummary/{order_id}', 'OrderController@orderSummary')->name('orderSummary')->middleware('checkAuthOrders');
});



