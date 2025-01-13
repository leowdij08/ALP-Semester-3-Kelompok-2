<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanPertanggungjawabansTable extends Migration
{
    public function up()
    {
        Schema::create('laporan_pertanggungjawaban', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->unsignedBigInteger('id_acara');
            $table->longtext('dokumenlpj');
            $table->boolean('diterima');
            $table->integer('revisike');
            $table->timestamps();

            $table->foreign('id_acara')->references('id_acara')->on('event_organisasi')->onDelete("cascade");
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_pertanggungjawaban');
    }
}
