<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePenanggungJawabOrganisasisTable extends Migration
{
    public function up()
    {
        Schema::create('user_organisasi', function (Blueprint $table) {
            $table->id('id_organisasi');
            $table->string('namaorganisasi', 45);
            $table->enum('kotadomisiliorganisasi', ['Makassar', 'Jakarta', 'Surabaya']);
            $table->string('nomorteleponorganisasi');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references("id")->on("users")->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('penanggung_jawab_organisasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_organisasi');
            $table->foreign('id_organisasi')->references("id_organisasi")->on("user_organisasi")->onDelete('cascade');
            $table->string('namalengkappjo', 45);
            $table->date('tanggallahirpjo');
            $table->string('emailpjo', 45);
            $table->string('alamatlengkappjo', 45);
            $table->timestamps();
        });
        DB::statement("ALTER TABLE penanggung_jawab_organisasi ADD ktppjo LONGBLOB");
    }

    public function down()
    {
        Schema::dropIfExists('penanggung_jawab_organisasi');
        Schema::dropIfExists('user_organisasi');
    }
}

