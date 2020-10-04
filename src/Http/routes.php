<?php

use Illuminate\Support\Facades\Route;

Route::prefix('gauge-api')->group(function () {
    Route::post('requests/metrics', 'Api\RequestMetricsController@show');

    Route::post('queries/metrics', 'Api\QueryMetricsController@show');
});

Route::get('/', 'DashboardController@index')->name('gauge.dashboard');
Route::get('/requests', 'RequestsController@index')->name('gauge.requests');
Route::get('/requests/{familyHash}', 'RequestsController@show')->name('gauge.request');
Route::get('/queries', 'QueriesController@index')->name('gauge.queries');
