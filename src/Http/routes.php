<?php

use Illuminate\Support\Facades\Route;

Route::get('/gauge-api/requests', 'RequestsController@index');
Route::post('/gauge-api/requests/metrics', 'RequestMetricsController@show');

Route::get('/gauge-api/queries', 'QueriesController@index');
Route::post('/gauge-api/queries/metrics', 'QueryMetricsController@show');
