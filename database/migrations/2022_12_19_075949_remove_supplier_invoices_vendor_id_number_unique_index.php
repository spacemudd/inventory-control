<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveSupplierInvoicesVendorIdNumberUniqueIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_invoices', function (Blueprint $table) {
          
            //$table->unique(['vendor_id', 'number']);
        //$table->dropUnique('supplier_invoices_number_unique');
            //$table->dropUnique('supplier_invoices_vendor_id_number_unique');
            $table->integer('vendor_id')->unsigned()->change();
            
            //$table->string('number')->unique(false)->change();
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
