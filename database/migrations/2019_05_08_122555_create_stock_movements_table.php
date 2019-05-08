<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('stock_id');
            $table->foreign('stock_id')->references('id')->on('stocks');
            $table->unsignedBigInteger('material_request_item_id')->nullable();
            $table->foreign('material_request_item_id')->references('id')->on('material_requests')->onDelete('set null');
            $table->unsignedBigInteger('quotation_item_id')->nullable();
            $table->foreign('quotation_item_id')->references('id')->on('quotation_items')->onDelete('set null');
            $table->integer('in');
            $table->integer('out');
            $table->nullableMorphs('stockable');
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
        Schema::dropIfExists('stock_movements');
    }
}
