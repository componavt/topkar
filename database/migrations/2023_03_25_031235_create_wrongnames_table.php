<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWrongnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wrongnames', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('toponym_id')->unsigned();
            
            $table->smallInteger('lang_id')->unsigned()->nullable();
            $table->     foreign('lang_id')->references('id')->on('langs');
            
            $table->string('name', 255)->collation('utf8_bin');
            $table->string('name_for_search', 255)->collation('utf8_bin');
            
            //$table->timestamps();
            $table->unique(['toponym_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wrongnames');
    }
}
