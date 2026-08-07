<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/docs', 'swagger')->name('swagger.docs');
Route::view('/api/documentation', 'swagger')->name('api.documentation');
