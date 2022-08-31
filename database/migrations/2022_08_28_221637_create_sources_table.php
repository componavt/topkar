<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('toponym_id')->unsigned();
            $table->string('mention', 128)->collation('utf8_bin')->nullable();
            $table->string('source', 256)->collation('utf8_bin');
            $table->smallInteger('sequence_number')->default(1);
//            $table->tinyInteger('is_map')->default(0);
            //$table->timestamps();
//            $table->unique(['toponym_id', 'source']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sources');
    }
}
