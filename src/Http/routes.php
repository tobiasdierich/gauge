<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'gauge.'], function () {
    Route::prefix('gauge-api')->group(function () {
        Route::post('requests/metrics', 'Api\RequestMetricsController@show');

        Route::post('queries/metrics', 'Api\QueryMetricsController@show');
    });

    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/requests', 'RequestsController@index')->name('requests.index');
    Route::get('/requests/{familyHash}', 'RequestsController@show')->name('requests.show');
    Route::get('/queries', 'QueriesController@index')->name('queries.index');
});
