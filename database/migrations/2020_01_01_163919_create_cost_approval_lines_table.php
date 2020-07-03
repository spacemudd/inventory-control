<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostApprovalLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_approval_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cost_approval_id')->unsigned();
            $table->foreign('cost_approval_id')->references('id')->on('cost_approvals')->onDelete('cascade');
            $table->string('description');
            $table->decimal('unit_price', 8, 3);
            $table->decimal('qty', 8, 3);
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
        Schema::dropIfExists('cost_approval_lines');
    }
}
