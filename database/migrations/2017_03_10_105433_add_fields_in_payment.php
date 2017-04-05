<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInPayment extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('payments', function(Blueprint $table){
            $table->uuid('cardinity_id');
            $table->string('order_id', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('payments', function(Blueprint $table){
            $table->dropColumn('cardinity_id');
            $table->dropColumn('order_id');
        });
    }
}
