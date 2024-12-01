<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanPertanggungjawabanTable extends Migration
{
    public function up()
    {
        Schema::create('laporan_pertanggungjawabans', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->unsignedBigInteger('id_organisasi');
            $table->unsignedBigInteger('id_transaksi');
            $table->text('deskripsikegiatan');
            $table->date('tanggallaporan');
            $table->binary('filelaporan');
            $table->timestamps();

            $table->foreign('id_organisasi')->references('id')->on('user_organisasis');
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksis');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_pertanggungjawabans');
    }
}
