<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfohmtctaggedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infohmtctagged', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('infohmtc_id')->unsigned();
            $table->foreign('infohmtc_id')->references('id')->on('infohmtcs')->onDelete('cascade');
            $table->integer('infohmtctag_id')->unsigned();
            $table->foreign('infohmtctag_id')->references('id')->on('infohmtctags')->onDelete('cascade');
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
        Schema::dropIfExists('infohmtctagged');
    }
}
