<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersAddNewPartnersFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_title')->default('');
            $table->string('name_2')->default('');
            $table->string('surname_2')->default('');
            $table->string('email_2')->default('');
            $table->string('vat')->default('');
            $table->smallInteger('phone_country_code_2')->default(0);
            $table->string('phone_2')->default('');
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
            $table->dropColumn(
                'company_title',
                'name_2',
                'surname_2',
                'email_2',
                'vat',
                'phone_country_code_2',
                'phone_2'
            );
        });
    }
}
