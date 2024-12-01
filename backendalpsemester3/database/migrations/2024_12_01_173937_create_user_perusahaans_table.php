<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPerusahaanTable extends Migration
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
    }

    public function down()
    {
        Schema::dropIfExists('user_perusahaan');
    }
}
