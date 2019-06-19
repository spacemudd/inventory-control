<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegionIdColToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material_requests', function (Blueprint $table) {
            $table->integer('region_id')->nullable()->references('id')->on('regions')->onDelete('set NULL');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->integer('region_id')->nullable()->references('id')->on('regions')->onDelete('set NULL');
        });

        Schema::table('job_orders', function (Blueprint $table) {
            $table->integer('region_id')->nullable()->references('id')->on('regions')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('material_requests', function (Blueprint $table) {
	    $table->dropForeign(['region_id']);
            $table->dropColumn(['region_id']);
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropColumn(['region_id']);
        });

        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropColumn(['region_id']);
        });
    }
}
