<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesanChatTable extends Migration
{
    public function up()
    {
        Schema::create('chat', function (Blueprint $table) {
            $table->id('id_chat');
            $table->foreignId('id_perusahaan')->constrained('user_perusahaan')->onDelete('cascade');
            $table->foreignId('id_organisasi')->constrained('user_organisasi')->onDelete('cascade');
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
            $table->foreign('id_chat')->references('id_chat')->on('chat');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pesan_chat');
        Schema::dropIfExists('chat');
    }
}