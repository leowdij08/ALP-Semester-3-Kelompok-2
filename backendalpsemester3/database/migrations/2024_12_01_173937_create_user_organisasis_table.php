<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOrganisasiTable extends Migration
{
    public function up()
    {
        Schema::create('user_organisasi', function (Blueprint $table) {
            $table->id('id_organisasi');
            $table->string('namaorganisasi', 45);
            $table->string('emailorganisasi', 45);
            $table->enum('kotadomisiliorganisasi', ['Makassar', 'Jakarta', 'Surabaya']);
            $table->string('nomorteleponorganisasi');
            $table->string('katasandiorganisasi', 45);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_organisasi');
    }
}
