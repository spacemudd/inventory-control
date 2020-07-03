<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostApprovalsCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cost_approvals', function (Blueprint $table) {
            $table->string('purpose_of_request')->nullable();
            $table->string('prepared_by_text')->nullable();
            $table->string('approved_by_text')->nullable();
            $table->boolean('due_diligence_approved')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cost_approvals', function (Blueprint $table) {
            $table->dropColumn(['purpose_of_request', 'prepared_by_text', 'approved_by_text', 'due_diligence_approved']);
        });
    }
}
