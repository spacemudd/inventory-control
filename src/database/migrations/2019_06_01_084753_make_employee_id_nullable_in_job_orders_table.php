<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeEmployeeIdNullableInJobOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->unsignedInteger('employee_id')->nullable()->onDelete('set null')->change();
            $table->string('ext')->nullable()->change();
            $table->string('remark')->nullable()->change();
            $table->dateTime('time_end')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->unsignedInteger('employee_id')->change();
            $table->string('ext')->change();
            $table->string('remark')->change();
            $table->dateTime('time_end')->change();
        });
    }
}
