<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameSourcesToSourceToponymTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
/*        Schema::table('sources', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
         });*/

        Schema::rename('sources', 'source_toponym');

        Schema::table('source_toponym', function (Blueprint $table) {
            $table->foreign('toponym_id')->references('id')->on('toponyms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('source_toponym', function (Blueprint $table) {
            $table->dropForeign(['toponym_id']);
        });
         
        Schema::rename('source_toponym', 'sources');
    }
}
