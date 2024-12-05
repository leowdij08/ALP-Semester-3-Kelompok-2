<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenanggungJawabPerusahaanTable extends Migration
{
    public function up()
    {
        Schema::create('user_perusahaan', function (Blueprint $table) {
            $table->id('id_perusahaan');
            $table->string('namaperusahaan', 45);
            $table->string('emailperusahaan', 45);
            $table->enum('kotadomisiliperusahaan', ['Makassar', 'Jakarta', 'Surabaya']);
            $table->string('nomorteleponperusahaan');
            $table->string('katasandiperusahaan', 45);
            $table->timestamps();
        });

        Schema::create('penanggung_jawab_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_perusahaan')->constrained('user_perusahaan')->onDelete('cascade');
            $table->string('namalengkappjp', 45);
            $table->string('tanggallahirpjp', 45);
            $table->string('emailpjp', 45);
            $table->string('alamatlengkappjp', 45);
            $table->binary('ktppjp');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penanggung_jawab_perusahaan');
        Schema::dropIfExists('user_perusahaan');
    }
}
