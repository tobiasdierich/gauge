<?php

use Illuminate\Support\Facades\Route;

Route::get('/gauge-api/requests', 'RequestsController@index');
Route::post('/gauge-api/requests/metrics', 'RequestMetricsController@show');
