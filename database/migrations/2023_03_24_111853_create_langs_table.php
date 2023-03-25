<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('langs', function (Blueprint $table) {
            //$table->increments('id');
            $table->tinyInteger('id')->unsigned()->autoIncrement(); // MySQL smallint(6)
            
            $table->string('name_en', 64);
            $table->string('name_ru', 64);
            
            /* code of language (e.g. 'en', 'ru').  */
            $table->string('code', 3)->unique();
            $table->tinyInteger('sequence_number')->unsigned();

            // $table->timestamps(); // disabled
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('langs');
    }
}
//alter table toponyms drop foreign key `toponyms_lang_id_foreign`;
//alter table toponyms drop index `toponyms_lang_id_foreign`;
//alter table langs change `id` `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT;
//alter table toponyms change `lang_id` `lang_id` tinyint(2) unsigned default NULL;
//ALTER TABLE toponyms ADD FOREIGN KEY (`lang_id`) REFERENCES langs(id);   

