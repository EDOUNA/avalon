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
    return view('mainLayout');
});

Route::group(['namespace' => 'bank'], function () {
    Route::get('/bank/transactions', 'BankController@transactions');
    Route::post('/bank/transactions/updateCategory', 'TransactionsController@updateCategory');
    Route::get('/bank/categories', 'CategoriesController@index');

    // API module specific routs
    Route::get('/bank/transactions/api/getCategorizedScore', 'TransactionsController@getCategorizedScore');
});

Route::group(['namespace' => 'meter'], function () {
    Route::get('/meter/liveUI', 'MeterController@liveUI');

    Route::get('/meter/api/getDomoticzData', 'MeterController@getDomoticzData');
    Route::get('/meter/api/getMeasurements/{deviceID}', 'DevicesController@getMeasurements');
});
