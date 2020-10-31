<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTheIdColumnToBigInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Dropping the Equipment ID Foreign Keys in all the tables in order to change the type then 
        re-consruct them again */
        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropForeign(['equipment_id']);
        });
        Schema::table('contract_equipments', function (Blueprint $table) {
            $table->dropForeign(['equipment_id']);
        });
        Schema::table('equipments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });
        Schema::table('job_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('equipment_id')->change();
            $table->foreign('equipment_id')->references('id')->on('equipments');
        });
        Schema::table('contract_equipments', function (Blueprint $table) {
            $table->unsignedBigInteger('equipment_id')->change();
            $table->foreign('equipment_id')->references('id')->on('equipments');
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropForeign(['equipment_id']);
        });
        Schema::table('contract_equipments', function (Blueprint $table) {
            $table->dropForeign(['equipment_id']);
        });
        Schema::table('equipments', function (Blueprint $table) {
            $table->increments('id')->change();
        });
        Schema::table('job_orders', function (Blueprint $table) {
            $table->unsignedInteger('equipment_id')->change();
            $table->foreign('equipment_id')->references('id')->on('equipments');
        });
        Schema::table('contract_equipments', function (Blueprint $table) {
            $table->unsignedInteger('equipment_id')->change();
            $table->foreign('equipment_id')->references('id')->on('equipments');
        });
    }
}