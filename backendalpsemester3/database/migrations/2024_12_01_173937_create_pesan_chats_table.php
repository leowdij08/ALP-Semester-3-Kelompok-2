<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesanChatsTable extends Migration
{
    public function up()
    {
        Schema::create('chat', function (Blueprint $table) {
            $table->id('id_chat');
            $table->unsignedBigInteger('id_organisasi');
            $table->index('id_organisasi');
            $table->foreign('id_organisasi')->references("id_organisasi")->on("user_organisasi")->onDelete('cascade');
            $table->unsignedBigInteger('id_perusahaan');
            $table->index('id_perusahaan');
            $table->foreign('id_perusahaan')->references("id_perusahaan")->on("user_perusahaan")->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('pesan_chat', function (Blueprint $table) {
            $table->id('id_pesan');
            $table->unsignedBigInteger('id_chat');
            $table->string('pengirim', 45);
            $table->string('penerima', 45);
            $table->time('waktukirim');
            $table->enum('statusbaca', ['Sudah', 'Belum']);
            $table->time('waktubaca')->nullable();
            $table->string('pesan', 200);
            $table->foreign('id_chat')->references('id_chat')->on('chat')->onDelete("cascade");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pesan_chat');
        Schema::dropIfExists('chat');
    }
}
