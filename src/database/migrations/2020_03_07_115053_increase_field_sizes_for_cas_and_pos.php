<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseFieldSizesForCasAndPos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cost_approval_lines', function (Blueprint $table) {
            $table->string('description', 800)->nullable()->change();
        });

        Schema::table('purchase_order_lines', function (Blueprint $table) {
            $table->string('description', 800)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
