<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenarikanOrganisasisTable extends Migration
{
    public function up()
    {
        Schema::create('penarikan_organisasi', function (Blueprint $table) {
            $table->id('id_penarikan');
            $table->unsignedBigInteger('id_rekeningorganisasi');
            $table->integer('jumlahdanaditarik');
            $table->datetime('tanggalpenarikan');
            $table->longtext('buktipenarikan');
            $table->timestamps();

            $table->foreign('id_rekeningorganisasi')->references('id_rekeningorganisasi')->on('rekening_organisasi')->onDelete("cascade");
        });
    }

    public function down()
    {
        Schema::dropIfExists('penarikan_organisasi');
    }
}
