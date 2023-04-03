<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNameSize2InSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->string('name_ru', 150)->collation('utf8_bin')->change();
            $table->string('name_en', 150)->collation('utf8_bin')->nullable()->change();
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
            $table->string('name_ru', 100)->collation('utf8_bin')->change();
            $table->string('name_en', 100)->collation('utf8_bin')->nullable()->change();
        });
    }
}
