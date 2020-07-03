<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostApprovalQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_approval_quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cost_approval_id');
            $table->foreign('cost_approval_id')->references('id')->on('cost_approvals');
            $table->string('quotation_number');
            $table->timestamps();

            $table->index(['quotation_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cost_approval_quotations');
    }
}
