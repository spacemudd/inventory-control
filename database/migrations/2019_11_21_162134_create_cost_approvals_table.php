<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('requested_by_id')->nullable();
            $table->foreign('requested_by_id')->references('id')->on('employees')->onDelete('SET NULL');
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->foreign('cost_center_id')->references('id')->on('departments')->onDelete('SET NULL');
            $table->string('project_location')->nullable();
            $table->dateTime('date')->nullable();

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
        Schema::dropIfExists('cost_approvals');
    }
}
