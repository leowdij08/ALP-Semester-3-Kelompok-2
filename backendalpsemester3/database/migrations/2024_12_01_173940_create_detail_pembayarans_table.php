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
            $table->unsignedBigInteger('id_rekeningtemu');
            $table->integer('biayasponsor');
            $table->integer('biayalayananaplikasi');
            $table->timestamps();

            $table->foreign('id_pembayaran')->references('id_pembayaran')->on('pembayaran_perusahaan')->onDelete("cascade");
            $table->foreign('id_rekeningtemu')->references('id_rekeningtemu')->on('rekening_temu')->onDelete("cascade");
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_pembayaran');
    }
}
