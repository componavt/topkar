<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreetStructTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('street_struct', function (Blueprint $table) {
            $table->integer('street_id')->unsigned();
            $table->foreign('street_id')->references('id')->on('streets');
            
            $table->smallInteger('struct_id')->unsigned();
            $table->foreign('struct_id')->references('id')->on('structs');
            
            $table->primary(['street_id', 'struct_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('street_struct');
    }
}
