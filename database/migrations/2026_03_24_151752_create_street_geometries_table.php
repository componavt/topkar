<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreetGeometriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('street_geometries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('street_id')->unique();
            $table->string('source_name')->nullable();
            $table->longText('geojson');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('street_id')->references('id')->on('streets')->cascadeOnDelete();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('street_geometries');
    }
}
