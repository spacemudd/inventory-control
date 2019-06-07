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

class DashboardController extends Controller
{
    public function index()
    {
        $mRequest = MaterialRequest::get();

        $stockCount = 0;
        $rfqsCount = count($mRequest);

        $PurchaseOrder = PurchaseOrder::get();
        $purchaseOrdersCount = count($PurchaseOrder);

        $quotations = Quotation::get();
        $deliveriesCount = count($quotations);

        $lowStockCount = 0;
        $outOfStockCount = 0;

    	return view('dashboard.index', compact('stockCount',
            'rfqsCount',
            'purchaseOrdersCount',
            'deliveriesCount',
            'lowStockCount',
            'outOfStockCount'
            ));
    }
}
