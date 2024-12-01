<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPembayaranTable extends Migration
{
    public function up()
    {
        Schema::create('detail_pembayarans', function (Blueprint $table) {
            $table->id('id_detailpembayaran');
            $table->unsignedBigInteger('id_pembayaran');
            $table->unsignedBigInteger('id_organisasi');
            $table->integer('nominaldonasi');
            $table->timestamps();

            $table->foreign('id_pembayaran')->references('id_pembayaran')->on('pembayaran_perusahaans');
            $table->foreign('id_organisasi')->references('id')->on('user_organisasis');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_pembayarans');
    }
}
