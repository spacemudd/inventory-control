<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->nullable();
            $table->tinyInteger('status');
            $table->timestamp('issued_at');
            $table->timestamp('expires_at');
            $table->unsignedInteger('vendor_id')->nullable();
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->string('vendor_reference')->nullable();
            $table->unsignedInteger('cost_center_id')->nullable();
            $table->foreign('cost_center_id')->references('id')->on('departments');
            $table->string('remarks', 1000)->nullable();
            $table->decimal('cost', 10, 2)->comment('Cost per payment');
            $table->string('payment_frequency');
            $table->decimal('total_cost', 10, 2)->comment('The total contract value');
            $table->unsignedInteger('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->timestamps();

            $table->unique(['vendor_id', 'vendor_reference']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
