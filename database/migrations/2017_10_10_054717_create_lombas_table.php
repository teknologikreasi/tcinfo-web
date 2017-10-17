<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLombasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lombas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('web');
            $table->date('deadline');
            $table->date('mulai_lomba');
            $table->date('selesai_lomba');
            $table->string('foto');
            $table->string('status', 1);
            $table->integer('added_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lombas');
    }
}
