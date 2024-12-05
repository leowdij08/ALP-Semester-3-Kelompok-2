<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranPerusahaansTable extends Migration
{
    public function up()
    {
        Schema::create('pembayaran_perusahaan', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_rekeningperusahaan');
            $table->integer('biayatotal');
            $table->date('tanggalpembayaran');
            $table->time('waktupembayaran');
            $table->binary('buktipembayaran');
            $table->timestamps();

            $table->foreign('id_rekeningperusahaan')->references('id_rekeningperusahaan')->on('rekening_perusahaan')->onDelete("cascade");
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran_perusahaan');
    }
}
