<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\AcaraController;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register_organisasi', 'register_organisasi');
    Route::post('register_perusahaan', 'register_perusahaan');
    Route::post('login', 'login');
    Route::get('login', "error_login")->name("login");
});

Route::controller(AcaraController::class)->prefix("acara")->group(function(){
    Route::get('getAll', "getAll");
    Route::get('search/{keyword}', "search");
    Route::post('filter', "filter");
});

Route::middleware('auth:sanctum')->group( function () {
    Route::get('/user', [RegisterController::class, "userData"]);
});
