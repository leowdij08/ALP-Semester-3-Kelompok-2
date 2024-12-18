<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenanggungJawabPerusahaansTable extends Migration
{
    public function up()
    {
        Schema::create('user_perusahaan', function (Blueprint $table) {
            $table->id('id_perusahaan');
            $table->string('namaperusahaan', 45);
            $table->enum('kotadomisiliperusahaan', ['Makassar', 'Jakarta', 'Surabaya']);
            $table->string('nomorteleponperusahaan');
            $table->unsignedBigInteger('id_user');
            $table->index('id_user');
            $table->foreign('id_user')->references("id")->on("users")->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('penanggung_jawab_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_perusahaan');
            $table->index('id_perusahaan');
            $table->foreign('id_perusahaan')->references("id_perusahaan")->on("user_perusahaan")->onDelete('cascade');
            $table->string('namalengkappjp', 45);
            $table->string('tanggallahirpjp', 45);
            $table->string('emailpjp', 45);
            $table->string('alamatlengkappjp', 45);
            $table->longtext('ktppjp');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penanggung_jawab_perusahaan');
        Schema::dropIfExists('user_perusahaan');
    }
}
