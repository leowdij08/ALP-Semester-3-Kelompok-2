<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventOrganisasiTable extends Migration
{
    public function up()
    {
        Schema::create('event_organisasi', function (Blueprint $table) {
            $table->id('id_event');
            $table->unsignedBigInteger('id_organisasi');
            $table->string('judul_event', 100);
            $table->string('deskripsi_event', 200);
            $table->string('lokasi_event', 100);
            $table->date('tanggalevent');
            $table->string('poster_event', 255)->nullable();
            $table->foreign('id_organisasi')->references('id_organisasi')->on('user_organisasi');
            $table->timestamps();
        });

        Schema::create('detail_waktu_acaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_event')->constrained('event_organisasi')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_waktu_acaras');
        Schema::dropIfExists('event_organisasi');
    }
}
