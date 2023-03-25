<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLangIdInTopnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topnames', function (Blueprint $table) {
            $table->smallInteger('lang_id')->unsigned()->after('id')->nullable();
            $table->     foreign('lang_id')->references('id')->on('langs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topnames', function (Blueprint $table) {
            $table->dropColumn('lang_id');
        });
    }
}
