<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('job_order_id');
            $table->unsignedInteger('stock_id');
            $table->unsignedInteger('technician_id');
            $table->integer('qty');
            $table->dateTime('dispatched_at')->nullable();
            $table->timestamps();

            $table->foreign('job_order_id')->references('id')->on('job_orders');
            $table->foreign('stock_id')->references('id')->on('stock');
            $table->foreign('technician_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_order_items');
    }
}
