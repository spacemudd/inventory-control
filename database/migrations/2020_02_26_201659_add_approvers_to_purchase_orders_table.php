<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApproversToPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->unsignedInteger('approver_one_id')->nullable();
            $table->foreign('approver_one_id')->references('id')->on('employees');
            $table->unsignedInteger('approver_two_id')->nullable();
            $table->foreign('approver_two_id')->references('id')->on('employees');
            $table->unsignedInteger('approver_three_id')->nullable();
            $table->foreign('approver_three_id')->references('id')->on('employees');
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
            $table->dropForeign(['approver_one_id']);
            $table->dropColumn(['approver_one_id']);
            $table->dropForeign(['approver_two_id']);
            $table->dropColumn(['approver_two_id']);
            $table->dropForeign(['approver_three_id']);
            $table->dropColumn(['approver_three_id']);
        });
    }
}