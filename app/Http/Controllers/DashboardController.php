<?php
/**
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Models\MaterialRequest;
use App\Models\Quotation;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $rfqsCount = MaterialRequest::count();

        $purchaseOrdersCount = PurchaseOrder::count();

        $deliveriesCount = Quotation::count();

        $inCount = StockMovement::select('in')->sum('in');
        $outCount = StockMovement::select('out')->sum('out');
        $stockCount = $inCount - $outCount;

        // todo:
        $lowStockCount = 0; 
//         query = DB::select(DB::raw("
//              SELECT stock.code,stock.category,stock.description,
//                 (stock.recomended_qty - StockMovement.qty_on_hand) AS diff
//              FROM stock JOIN StockMovement ON 
            
//              WHERE stock.recomended_qty - StockMovement.qty > 0
// "));


        // TODO: For every stock, check if the qty_on_hand is LESS THAN recomended_qty.
        $outOfStockCount = 0; // TODO: All items that have 0 qty_on_hand

    	return view('dashboard.index', compact('stockCount',
            'rfqsCount',
            'purchaseOrdersCount',
            'deliveriesCount',
            'lowStockCount',
            'outOfStockCount'
            ));
    }
   // public static function lowStockCount()
   // {
   //  $lowStockCount = dd(\DB::table('stock')->where('recomended_qty', '<', DB::raw
   //      ('qty_on_hand'))->get());

   //  return view('dashboard.index', compact('lowStockCount'));
   //  }
 
}
