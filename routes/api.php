<?php

Route::group(['prefix' => 'v1', 'middleware' => ['jwt.auth', 'api-header']], function () {

    // all routes to protected resources are registered here
    Route::get('users/list', function () {
        $users = App\User::all();

        $response = ['success' => true, 'data' => $users];
        return response()->json($response, 201);
    });
});

// Regular API routes that don't require authentication
Route::group(['prefix' => 'v1', 'middleware' => 'api-header'], function () {
    Route::post('user/login', 'API\UserController@login');
    Route::post('user/register', 'API\UserController@register');
});
