<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameLegendInToponyms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('toponyms', function (Blueprint $table) {
            $table->renameColumn('legend', 'main_info');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('toponyms', function (Blueprint $table) {
            $table->renameColumn('main_info', 'legend');
        });
    }
}
