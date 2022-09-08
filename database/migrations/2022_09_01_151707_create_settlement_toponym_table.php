<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettlementToponymTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlement_toponym', function (Blueprint $table) {          
            $table->smallInteger('settlement_id')->unsigned();
            $table->foreign('settlement_id')->references('id')->on('settlements');
            
            $table->integer('toponym_id')->unsigned();
            $table->foreign('toponym_id')->references('id')->on('toponyms');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settlement_toponym');
    }
}