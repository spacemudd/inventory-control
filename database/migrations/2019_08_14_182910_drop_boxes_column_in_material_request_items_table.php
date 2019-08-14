<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropBoxesColumnInMaterialRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material_request_items', function (Blueprint $table) {
            $table->dropColumn(['qty_boxes']);
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
            $table->decimal('qty_boxes', 10, 2);
        });
    }
}
