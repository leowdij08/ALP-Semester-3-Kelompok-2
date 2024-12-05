<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekeningTemusTable extends Migration
{
    public function up()
    {
        Schema::create('rekening_temu', function (Blueprint $table) {
            $table->id('id_rekeningtemu');
            $table->integer('nomorrekeningtemu');
            $table->enum('namabanktemu', ['SEABANK', 'BCA', 'BCA Digital']);
            $table->string('pemilikrekeningtemu', 45);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekening_temu');
    }
}
