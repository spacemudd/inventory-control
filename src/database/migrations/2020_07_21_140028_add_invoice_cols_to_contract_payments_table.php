<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceColsToContractPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_payments', function (Blueprint $table) {
            $table->timestamp('invoice_period_from')->nullable();
            $table->timestamp('invoice_period_to')->nullable();
            $table->timestamp('proceeded_date')->nullable();
            $table->string('invoice_no', 50)->nullable();
            $table->float('invoice_tax_amount', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_payments', function (Blueprint $table) {
            //
        });
    }
}
