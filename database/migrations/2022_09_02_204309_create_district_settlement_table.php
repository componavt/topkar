<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictSettlementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district_settlement', function (Blueprint $table) {
            $table->tinyInteger('district_id')->unsigned();
            $table->foreign('district_id')->references('id')->on('districts');
            
            $table->smallInteger('settlement_id')->unsigned();
            $table->foreign('settlement_id')->references('id')->on('settlements');
            
            $table->smallInteger('include_from')->unsigned()->nullable();
            $table->smallInteger('include_to')->unsigned()->nullable();
            
            $table->primary(['district_id', 'settlement_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('district_settlement');
    }
}
