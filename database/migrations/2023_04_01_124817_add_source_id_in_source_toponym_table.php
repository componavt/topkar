<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSourceIdInSourceToponymTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('source_toponym', function (Blueprint $table) {
            $table->tinyInteger('source_id')->unsigned()->after('mention')->nullable();
            $table->     foreign('source_id')->references('id')->on('sources');
            $table->renameColumn('source', 'source_text');
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
            $table->dropForeign(['source_id']);
            $table->dropColumn('source_id');
            $table->renameColumn('source_text', 'source');
        });
    }
}
