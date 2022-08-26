<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameForSearchInToponyms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('toponyms', function (Blueprint $table) {
            $table->string('name_for_search', 255)->after('name')->nullable()->collation('utf8_bin');
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
            $table->dropColumn('name_for_search');
        });
    }
}
