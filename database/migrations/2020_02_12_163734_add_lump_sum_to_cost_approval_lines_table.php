<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLumpSumToCostApprovalLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cost_approval_lines', function (Blueprint $table) {
            $table->boolean('lump_sum')->default(false)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cost_approval_lines', function (Blueprint $table) {
            $table->dropColumn(['lump_sum']);
        });
    }
}
