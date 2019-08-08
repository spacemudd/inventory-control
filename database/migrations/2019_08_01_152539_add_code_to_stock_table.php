<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use DB;

class AddCodeToStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock', function (Blueprint $table) {
          
          if(env('DB_DATABASE') === 'sqlsrv') {
             DB::statement('CREATE UNIQUE NONCLUSTERED INDEX stocks_code_uq ON dbo.inv_stocks(code) WHERE code IS NOT NULL;');
          } else {
             $table->string('code')->unique()->after('id')->nullable();
          }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock', function (Blueprint $table) {

            if(env('DB_DATABASE') === 'sqlsrv') {
                DB::statement('DROP INDEX stocks_code_uq ON dbo.inv_stocks;');
            } else {
                $table->dropColumn(['code']);
            }
          
        });
    }
}
