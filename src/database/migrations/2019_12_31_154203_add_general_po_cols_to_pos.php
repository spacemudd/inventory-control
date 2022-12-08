<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGeneralPoColsToPos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('to')->nullable();
            $table->string('subject')->nullable();
            $table->string('location')->nullable();
            
            $table->unsignedInteger('quotation_id')->nullable();
            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['quotation_id']);
            $table->dropColumn(['quotation_id', 'to', 'subject', 'location']);
        });
    }
}
