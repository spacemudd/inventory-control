<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_invoice_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_invoice_id')->unsignedD();
            $table->foreign('supplier_invoice_id')->references('id')->on('supplier_invoices');
            $table->string('description');
            $table->string('serial_number')->nullable();
            $table->string('tag_number')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('supplier_invoice_lines');
    }
}
