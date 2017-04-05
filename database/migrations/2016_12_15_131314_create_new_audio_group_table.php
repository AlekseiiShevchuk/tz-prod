<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewAudioGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->integer('audio_categories_id', false, true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('audio_categories_id');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audio_groups');
    }
}