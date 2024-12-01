<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenarikanOrganisasiTable extends Migration
{
    public function up()
    {
        Schema::create('penarikan_organisasis', function (Blueprint $table) {
            $table->id('id_penarikan');
            $table->unsignedBigInteger('id_organisasi');
            $table->unsignedBigInteger('id_rekeningorganisasi');
            $table->integer('jumlahdanaditarik');
            $table->date('tanggalpenarikan');
            $table->time('waktupenarikan');
            $table->binary('buktipenarikan');
            $table->timestamps();

            $table->foreign('id_organisasi')->references('id')->on('user_organisasis');
            $table->foreign('id_rekeningorganisasi')->references('id_rekeningorganisasi')->on('rekening_organisasis');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penarikan_organisasis');
    }
}
