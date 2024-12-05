<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_perusahaan');
            $table->unsignedBigInteger('id_organisasi');
            $table->string('judulproker', 50);
            $table->integer('nominalproker');
            $table->integer('nominaldonasi');
            $table->integer('jumlahprokerterdanai');
            $table->timestamps();

            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('user_perusahaan')->onDelete("cascade");
            $table->foreign('id_organisasi')->references('id_organisasi')->on('user_organisasi')->onDelete("cascade");
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}
