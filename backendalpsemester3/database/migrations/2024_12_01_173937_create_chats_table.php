<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatTable extends Migration
{
    public function up()
    {
        Schema::create('chat', function (Blueprint $table) {
            $table->id('id_chat');
            $table->foreignId('id_perusahaan')->constrained('user_perusahaan')->onDelete('cascade');
            $table->foreignId('id_organisasi')->constrained('user_organisasi')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat');
    }
}
