<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPembayaransTable extends Migration
{
    public function up()
    {
        Schema::create('detail_pembayaran', function (Blueprint $table) {
            $table->id('id_detailpembayaran');
            $table->unsignedBigInteger('id_pembayaran');
            $table->unsignedBigInteger('id_rekeningorganisasi');
            $table->integer('nominaldonasi');
            $table->timestamps();

            $table->foreign('id_pembayaran')->references('id_pembayaran')->on('pembayaran_perusahaan')->onDelete("cascade");
            $table->foreign('id_rekeningorganisasi')->references('id_rekeningorganisasi')->on('rekening_organisasi')->onDelete("cascade");
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_pembayaran');
    }
}
