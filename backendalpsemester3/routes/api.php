<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\AcaraController;
use App\Http\Controllers\API\UserPerusahaanController;
use App\Http\Controllers\API\UserOrganisasiController;
use App\Http\Controllers\API\LaporanPertanggungJawabanController;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register_organisasi', 'register_organisasi');
    Route::post('register_perusahaan', 'register_perusahaan');
    Route::post('login', 'login');
    Route::get('login', "error_login")->name("login");
});

Route::controller(AcaraController::class)->prefix("acara")->group(function(){
    Route::get('getAll', "getAll");
    Route::get('{idAcara}', "getById");
    Route::get('search/{keyword}', "search");
    Route::post('filter', "filter");
    Route::put('{idAcara}', "update");
});

Route::middleware('auth:sanctum')->group( function () {
    Route::get('/user', [RegisterController::class, "userData"]);
});

Route::controller(UserPerusahaanController::class)->prefix("perusahaan")->group(function(){
    Route::get('{id}', "getbyID");
    Route::get('search/{keyword}', "search");
});

Route::controller(UserOrganisasiController::class)->prefix("organisasi")->group(function(){
    Route::get('/{id}', "getbyID");
    Route::get('/search/{keyword}', "search");
});

Route::controller(LaporanPertanggungJawabanController::class)->prefix("perusahaan")->group(function(){
    Route::get('{id}', "getbyID");
    Route::get('search/{keyword}', "search");
});
