<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderForGroupsAndCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audio_groups', function (Blueprint $table) {
            $table->integer('order')->nullable(false)->default(0);
        });
        Schema::table('sounds', function (Blueprint $table) {
            $table->integer('order')->nullable(false)->default(0);
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
            $table->dropColumn('order');
        });
        Schema::table('sounds', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
