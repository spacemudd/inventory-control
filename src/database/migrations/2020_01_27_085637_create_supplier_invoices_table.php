<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id')->unsigned();
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->integer('purchase_order_id')->unsigned()->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            $table->string('po_number')->nullable();
            $table->string('number');
            $table->date('proceeded_date')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unique(['vendor_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_invoices');
    }
}
