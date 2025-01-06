<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLampiranPesansTable extends Migration
{
    public function up()
    {
        Schema::create('lampiran_pesan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pesan');
            $table->index('id_pesan');
            $table->index('id_pesan');
            $table->enum('tipelampiran', ['Foto', 'Dokumen']);
            $table->string('namafile', 100);
            $table->longtext('urlfile');
            $table->foreign('id_pesan')->references('id_pesan')->on('pesan_chat')->onDelete("cascade");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lampiran_pesan');
    }
}
