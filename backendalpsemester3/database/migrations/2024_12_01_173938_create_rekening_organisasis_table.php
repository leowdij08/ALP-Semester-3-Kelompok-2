<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekeningOrganisasiTable extends Migration
{
    public function up()
    {
        Schema::create('rekening_organisasis', function (Blueprint $table) {
            $table->id('id_rekeningorganisasi');
            $table->integer('nomorrekeningorganisasi');
            $table->enum('namabankorganisasi', ['BCA', 'BCA DIGITAL', 'Mandiri', 'BNI', 'DBS']);
            $table->string('pemilikrekeningorganisasi', 45);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekening_organisasis');
    }
}
