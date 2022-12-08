<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('purchase_order_id')->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->string('description');
            $table->string('item_id')->nullable();
            $table->float('unit_price', 10, 2);
            $table->float('qty', 10, 2);
            $table->float('subtotal', 10, 2);
            $table->float('vat', 10, 2);
            $table->float('grand_total', 10, 2);
            $table->boolean('lump_sum')->default(false);
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
        Schema::dropIfExists('purchase_order_lines');
    }
}
