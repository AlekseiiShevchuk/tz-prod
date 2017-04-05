<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersAddNewFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->char('iso', 2)->unique();
            $table->string('name', 80)->unique();
            $table->string('nicename', 80)->unique();
            $table->char('iso3', 3)->nullable();
            $table->smallInteger('numcode')->nullable();
            $table->integer('phonecode')->nullable();
        });

        DB::table('countries')->insert(['id' => 1, 'iso' => '', 'name' => 'default', 'nicename' => '', 'iso3' => '', 'numcode' => 0, 'phonecode' => 0]);

        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->default('');
            $table->string('nickname')->default('');
            $table->date('birthday')->nullable();
            $table->enum('gender', ['man', 'woman', ''])->default('');
            $table->integer('country_id', false, true)->default(1);
            $table->string('city')->default('');
            $table->string('zip')->default('');
            $table->string('address1')->default('');
            $table->string('address2')->default('');
            $table->smallInteger('phone_country_code')->default(0);
            $table->string('phone')->default('');
            $table->string('image')->default('');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('countries');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(
                'surname',
                'nickname',
                'birthday',
                'gender',
                'country_id',
                'city',
                'zip',
                'address1',
                'address2',
                'phone_country_code',
                'phone',
                'image'
            );
        });
    }
}
