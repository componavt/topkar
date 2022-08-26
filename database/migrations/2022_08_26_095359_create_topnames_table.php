<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topnames', function (Blueprint $table) {
            $table->integer('toponym_id')->unsigned();
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
        Schema::dropIfExists('topnames');
    }
}
