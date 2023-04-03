<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeShortSizeInSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->string('short_ru', 50)->collation('utf8_bin')->nullable()->change();
            $table->string('short_en', 50)->collation('utf8_bin')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->string('short_ru', 32)->collation('utf8_bin')->nullable()->change();
            $table->string('short_en', 32)->collation('utf8_bin')->nullable()->change();
        });
    }
}
