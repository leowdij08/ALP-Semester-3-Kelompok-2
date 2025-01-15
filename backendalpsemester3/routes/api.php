<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\AcaraController;
use App\Http\Controllers\API\UserPerusahaanController;
use App\Http\Controllers\API\UserOrganisasiController;
use App\Http\Controllers\API\LaporanPertanggungJawabanController;
use App\Http\Controllers\API\PenanggungJawabOrganisasiController;
use App\Http\Controllers\API\PenanggungJawabPerusahaanController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\PembayaranPerusahaanController;
use App\Http\Controllers\API\RekeningPerusahaanController;
use App\Http\Controllers\API\RekeningTemuController;
use App\Http\Controllers\API\RekeningOrganisasiController;
use App\Http\Controllers\API\PenarikanOrganisasiController;

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
    Route::delete('{idAcara}', "delete");
});

Route::controller(ChatController::class)->prefix("chat")->group(function(){
    Route::get('', "getAll");
    Route::get('{idChat}', "getById");
    Route::post('', "sendChat");
});

Route::middleware('auth:sanctum')->group( function () {
    Route::get('/user', [RegisterController::class, "userData"]);
});

Route::controller(UserPerusahaanController::class)->prefix("perusahaan")->group(function(){
    Route::get('', "getAll");
    Route::get('{idPerusahaan}', "getbyID");
    Route::get('search/{keyword}', "search");
    Route::put('', "update");
});

Route::controller(UserOrganisasiController::class)->prefix("organisasi")->group(function(){
    Route::get('', "getAll");
    Route::get('/{idOrganisasi}', "getbyID");
    Route::get('/search/{keyword}', "search");
    Route::put('', "update");

});

Route::controller(LaporanPertanggungJawabanController::class)->prefix("laporan")->group(function(){
    Route::get('{id}', "getbyID");
    Route::get('search/{keyword}', "search");
    Route::put('{idAcara}', "update");
    Route::delete('{idAcara}', "delete");
    Route::post('{idAcara}', "create");
});

Route::controller(PenanggungJawabOrganisasiController::class)->prefix("penanggungjawaborganisasi")->group(function(){
    Route::get('{id}', "getbyID");
    Route::get('search/{keyword}', "search");
    Route::put('', "update");
});

Route::controller(PenanggungJawabPerusahaanController::class)->prefix("penanggungjawabperusahaan")->group(function(){
    Route::get('{id}', "getbyID");
    Route::get('search/{keyword}', "search");
    Route::put('', "update");
});

Route::controller(PembayaranPerusahaanController::class)->prefix("pembayaranperusahaan")->group(function() {
    Route::get('', "getAll");
    Route::get('{idPembayaran}', "getbyID");
    Route::post('{idAcara}', "create");
});

Route::controller(RekeningPerusahaanController::class)->prefix("rekeningperusahaaan")->group(function(){
    Route::get('', "getByPerusahaan");
    Route::put('', "update");
    Route::post('', "create");
    Route::delete('', "delete");
});

Route::controller(RekeningTemuController::class)->prefix("rekeningtemu")->group(function () {
    Route::get('{id}', "getById");
    Route::get('search/{keyword}', "search");
    Route::post('', "create");
    Route::put('{idRekeningTemu}', "update");
    Route::delete('{idRekeningTemu}', "delete");
});

Route::controller(RekeningOrganisasiController::class)->prefix("rekeningorganisasi")->group(function(){
    Route::get('', "getByPerusahaan");
    Route::put('', "update");
    Route::post('', "create");
    Route::delete('', "delete");
});

Route::controller(PenarikanOrganisasiController::class)->prefix("penarikanorganisasi")->group(function() {
    Route::get('', "getAll");
    Route::get('{id}', "getById");
    Route::post('', "create");
    Route::put('{id}', "update");
    Route::delete('{id}', "delete");
});
