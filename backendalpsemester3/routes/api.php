<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\AcaraController;
use App\Http\Controllers\API\UserPerusahaanController;
use App\Http\Controllers\API\UserOrganisasiController;
use App\Http\Controllers\API\LaporanPertanggungJawabanController;
use App\Http\Controllers\API\PenanggungJawabOrganisasiController;
use App\Http\Controllers\API\PenanggungJawabPerusahaanController;
use App\Http\Controllers\API\PesanChatController;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register_organisasi', 'register_organisasi');
    Route::post('register_perusahaan', 'register_perusahaan');
    Route::post('login', 'login');
    Route::get('login', "error_login")->name("login");
});

Route::controller(AcaraController::class)->prefix("acara")->group(function(){
    Route::get('', "getAll");
    Route::get('{idAcara}', "getById");
    Route::get('search/{keyword}', "search");
    Route::post('filter', "filter");
    Route::post('', "create");
    Route::put('{idAcara}', "update");
});

Route::controller(PesanChatController::class)->prefix("pesanChat")->group(function(){
    Route::get('', "getAll");
    Route::get('{idAcara}', "getById");
    Route::get('search/{keyword}', "search");
    Route::post('filter', "filter");
    Route::post('', "create");
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
    Route::put('', "update");

});

Route::controller(LaporanPertanggungJawabanController::class)->prefix("laporan")->group(function(){
    Route::get('{id}', "getbyID");
    Route::get('search/{keyword}', "search");
});

Route::controller(PenanggungJawabOrganisasiController::class)->prefix("penanggungjawaborganisasi")->group(function(){
    Route::get('{id}', "getbyID");
    Route::get('search/{keyword}', "search");
});

Route::controller(PenanggungJawabPerusahaanController::class)->prefix("penanggungjawabperusahaan")->group(function(){
    Route::get('{id}', "getbyID");
    Route::get('search/{keyword}', "search");
});
