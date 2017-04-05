<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteNameIso3Numcode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn(['name', 'iso3', 'numcode']);
            $table->renameColumn('nicename', 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->renameColumn('name','nicename');
            $table->string('name', 80)->unique();
            $table->char('iso3', 3)->nullable();
            $table->smallInteger('numcode')->nullable();
        });
    }
}
