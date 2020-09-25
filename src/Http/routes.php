<?php

use Illuminate\Support\Facades\Route;

Route::prefix('gauge-api')->group(function () {
    Route::get('requests', 'Api\RequestsController@index');
    Route::post('requests/metrics', 'Api\RequestMetricsController@show');

    Route::get('queries', 'Api\QueriesController@index');
    Route::post('queries/metrics', 'Api\QueryMetricsController@show');
});
