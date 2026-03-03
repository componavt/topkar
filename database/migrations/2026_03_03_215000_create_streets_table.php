<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('streets', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name_ru', 150);
            $table->string('name_krl', 150)->nullable();
            $table->string('name_fin', 150)->nullable();

            $table->unsignedSmallInteger('geotype_id')->nullable();
            $table->text('history')->nullable();

            $table->timestamps();

            // Индексы для ускорения поиска
            $table->index('name_ru');
            $table->index('geotype_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('streets');
    }
}
