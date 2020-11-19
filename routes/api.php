<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'  => 'App\Http\Controllers\Api',
], function () {
    Route::get("/", "BasicController@index");
    Route::post("/login", "BasicController@login");
    Route::get("/counter_mysql", "BasicController@counter_mysql");
    Route::get("/counter_mongo", "BasicController@counter_mongo");

    Route::group([
        'middleware' => [
            'auth:api'
        ],
    ], function () {
        Route::get("/user", "BasicController@user");
    });
});
