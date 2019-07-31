<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeQtyBoxesToDecimalInMaterialRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material_request_items', function (Blueprint $table) {
            $table->decimal('qty', 15, 2)->default(1)->comment('Pieces qty')->change();
            $table->decimal('qty_boxes', 15, 2)->default(0)->comment('Boxes qty')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('material_request_items', function (Blueprint $table) {
            //
        });
    }
}
