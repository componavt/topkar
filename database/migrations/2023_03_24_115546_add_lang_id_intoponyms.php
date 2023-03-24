<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLangIdIntoponyms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('toponyms', function (Blueprint $table) {
            $table->smallInteger('lang_id')->unsigned()->after('id');
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
        Schema::table('toponyms', function (Blueprint $table) {
            $table->dropColumn('lang_id');
        });
    }
}
