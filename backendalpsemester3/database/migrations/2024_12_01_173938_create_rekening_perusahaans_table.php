<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekeningPerusahaanTable extends Migration
{
    public function up()
    {
        Schema::create('rekening_perusahaans', function (Blueprint $table) {
            $table->id('id_rekeningperusahaan');
            $table->integer('nomorrekeningperusahaan');
            $table->enum('namabankperusahaan', ['BCA', 'BCA Digital', 'SEABANK', 'Mandiri', 'BNI', 'DBS']);
            $table->string('pemilikrekeningperusahaan', 45);
            $table->boolean("isActive")->default(true);
            $table->foreignId('id_perusahaan')->constrained('user_perusahaan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekening_perusahaans');
    }
}
