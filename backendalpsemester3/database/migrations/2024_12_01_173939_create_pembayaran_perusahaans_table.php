<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePembayaranPerusahaansTable extends Migration
{
    public function up()
    {
        Schema::create('pembayaran_perusahaan', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_rekeningperusahaan');
            $table->unsignedBigInteger('id_acara');
            $table->integer('biayatotal');
            $table->datetime('tanggalpembayaran');
            $table->timestamps();

            $table->foreign('id_acara')->references('id_acara')->on('event_organisasi')->onDelete("cascade");
            $table->foreign('id_rekeningperusahaan')->references('id_rekeningperusahaan')->on('rekening_perusahaan')->onDelete("cascade");
        });
        DB::statement("ALTER TABLE pembayaran_perusahaan ADD buktipembayaran LONGBLOB");
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran_perusahaan');
    }
}
