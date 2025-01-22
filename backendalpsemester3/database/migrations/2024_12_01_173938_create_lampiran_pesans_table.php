<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLampiranPesansTable extends Migration
{
    public function up()
    {
        Schema::create('lampiran_pesan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pesan');
            $table->enum('tipelampiran', ['Foto', 'Dokumen']);
            $table->string('namafile', 100);
            $table->foreign('id_pesan')->references('id_pesan')->on('pesan_chat')->onDelete("cascade");
            $table->timestamps();
        });
        DB::statement("ALTER TABLE lampiran_pesan ADD urlfile LONGBLOB");
    }

    public function down()
    {
        Schema::dropIfExists('lampiran_pesan');
    }
}
