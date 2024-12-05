<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcarasTable extends Migration
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
            $table->foreign('id_organisasi')->references('id_organisasi')->on('user_organisasi')->onDelete("cascade");
            $table->timestamps();
        });

        Schema::create('detail_waktu_acara', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_event');
            $table->index('id_event');
            $table->foreign('id_event')->references("id_event")->on("event_organisasi")->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_waktu_acara');
        Schema::dropIfExists('event_organisasi');
    }
}
