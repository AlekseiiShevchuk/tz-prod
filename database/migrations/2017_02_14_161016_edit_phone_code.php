<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPhoneCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_country_code', 'phone_country_code_2'
            ]);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer('phone_country_code')->default(0);
            $table->integer('phone_country_code_2')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_country_code', 'phone_country_code_2'
            ]);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->smallInteger('phone_country_code')->default(0);
            $table->smallInteger('phone_country_code_2')->default(0);
        });
    }
}
