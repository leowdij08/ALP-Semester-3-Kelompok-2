<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenanggungJawabOrganisasiTable extends Migration
{
    public function up()
    {
        Schema::create('penanggung_jawab_organisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_organisasi')->constrained('user_organisasi')->onDelete('cascade');
            $table->string('namalengkappjo', 45);
            $table->string('tanggallahirpjo', 45);
            $table->string('emailpjo', 45);
            $table->string('alamatlengkappjo', 45);
            $table->binary('ktppjo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penanggung_jawab_organisasi');
    }
}
