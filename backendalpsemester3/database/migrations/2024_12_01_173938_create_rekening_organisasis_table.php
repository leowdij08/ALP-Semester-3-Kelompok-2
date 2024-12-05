<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekeningOrganisasisTable extends Migration
{
    public function up()
    {
        Schema::create('rekening_organisasi', function (Blueprint $table) {
            $table->id('id_rekeningorganisasi');
            $table->integer('nomorrekeningorganisasi');
            $table->enum('namabankorganisasi', ['BCA', 'BCA DIGITAL', 'Mandiri', 'BNI', 'DBS']);
            $table->string('pemilikrekeningorganisasi', 45);
            $table->boolean("isActive")->default(true);
            $table->unsignedBigInteger('id_organisasi');
            $table->index('id_organisasi');
            $table->foreign('id_organisasi')->references("id_organisasi")->on("user_organisasi")->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekening_organisasi');
    }
}
