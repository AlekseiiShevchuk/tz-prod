<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AudioGroupsForeignCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audio_groups', function (Blueprint $table) {
            $table->foreign('audio_categories_id')
                ->references('id')
                ->on('audio_categories')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audio_groups', function (Blueprint $table) {
            $table->dropForeign('audio_groups_audio_categories_id_foreign');
        });
    }
}
