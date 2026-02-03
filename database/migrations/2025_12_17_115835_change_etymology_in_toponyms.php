<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEtymologyInToponyms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('toponyms', function (Blueprint $table) {
            $table->text('etymology')->collation('utf8_bin')->nullable()->change();
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
            $table->string('etymology', 2048)->collation('utf8_bin')->nullable()->change();
        });
    }
}
