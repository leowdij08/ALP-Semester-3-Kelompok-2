<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_perusahaan');
            $table->unsignedBigInteger('id_organisasi');
            $table->string('judulproker', 50);
            $table->integer('nominalproker');
            $table->integer('nominaldonasi');
            $table->integer('jumlahprokerterdanai');
            $table->timestamps();

            $table->foreign('id_perusahaan')->references('id')->on('user_perusahaans');
            $table->foreign('id_organisasi')->references('id')->on('user_organisasis');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
}
