<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTextToponymTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_toponym', function (Blueprint $table) {
            $table->integer('text_id')->unsigned();
            
            $table->integer('toponym_id')->unsigned();
            $table->foreign('toponym_id')->references('id')->on('toponyms');
            
            $table->unique(['text_id', 'toponym_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('text_toponym');
    }
}
