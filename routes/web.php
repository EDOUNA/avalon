<?php

// Main index
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('mainLayout');
    });
});


Route::group(['middleware' => 'auth', 'namespace' => 'Bank'], function () {
    Route::get('/bank/transactions', 'BankController@transactions');
    Route::post('/bank/transactions/updateCategory', 'TransactionsController@updateCategory');
    Route::get('/bank/categories', 'CategoriesController@index');

    // API module specific routs
    // @TODO: merge these to api.php
    Route::get('/bank/transactions/api/getCategorizedScore', 'TransactionsController@getCategorizedScore');
});

Route::group(['middleware' => 'auth', 'namespace' => 'Meter'], function () {
    Route::get('/meter/liveUI', 'MeterController@liveUI');
    Route::get('/meter/static', 'MeterController@viewStatic');
    Route::get('/meter/budget', 'MeterController@budget');

    // @TODO: merge these to api.php
    Route::get('/meter/api/getDomoticzData', 'MeterController@getDomoticzData');
    Route::get('/meter/api/getMeasurements/{deviceID}', 'DevicesController@getMeasurements');
    Route::get('/meter/api/getActualTariffs/{deviceID}', 'DevicesController@getActualTariffs');
    Route::get('/meter/api/getDailyBudget', 'DevicesController@getDailyBudget');
    Route::get('/meter/api/renderDefaultMeasurements/{deviceID}', 'MeterController@renderDefaultMeasurements');
    Route::get('/meter/api/getBudget/{rangeType}', 'API\MeterAPIController@budget');
});

Auth::routes();
Route::get('/home', 'HomeController@index');
