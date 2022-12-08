<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_quotations', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('purchase_order_id')->unsigned()->nullable();
			$table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
			$table->string('quotation_number')->nullable();
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
        Schema::dropIfExists('purchase_order_quotations');
    }
}
