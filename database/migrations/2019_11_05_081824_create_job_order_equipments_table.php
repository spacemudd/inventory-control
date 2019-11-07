<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobOrderEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_equipments', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('equipment_id')->nullable();
            $table->unsignedInteger('job_order_id')->nullable();
            $table->unsignedInteger('technician_id')->nullable();

            $table->foreign('equipment_id')->references('id')->on('equipments')->onDelete('SET NULL');
            $table->foreign('job_order_id')->references('id')->on('job_orders')->onDelete('cascade');
            $table->foreign('technician_id')->references('id')->on('employees')->onDelete('SET NULL');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_order_equipments');
    }
}
