<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcarasTable extends Migration
{
    public function up()
    {
        Schema::create('event_organisasi', function (Blueprint $table) {
            $table->id('id_acara');
            $table->unsignedBigInteger('id_organisasi');
            $table->string('namaacara', 100);
            $table->date('tanggalacara');
            $table->string('lokasiacara', 100);
            $table->integer('biayadibutuhkan');
            $table->enum('kegiatanacara', ['Gunung', 'Pantai', 'Hutan']);
            $table->enum('kotaberlangsung', ['Jakarta', 'Surabaya', 'Makassar']);
            $table->longtext('poster_event');
            $table->foreign('id_organisasi')->references('id_organisasi')->on('user_organisasi')->onDelete("cascade");
            $table->timestamps();
        });

        Schema::create('detail_waktu_acara', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_acara');
            $table->index('id_acara');
            $table->foreign('id_acara')->references("id_acara")->on("event_organisasi")->onDelete('cascade');
            $table->time('waktumulai');
            $table->time('waktuselesai');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_waktu_acara');
        Schema::dropIfExists('event_organisasi');
    }
}
