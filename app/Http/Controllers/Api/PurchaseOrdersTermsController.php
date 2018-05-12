<?php

namespace App\Http\Controllers\Api;

use App\Model\PurchaseTerm;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseOrdersTermsController extends Controller
{
    public function attach(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'term_id' => 'required|exists:purchase_terms,id',
        ]);

        $po = PurchaseOrder::find($request->purchase_order_id);
        $term = PurchaseTerm::find($request->term_id);

        $po->terms()->attach($term);

        $po->terms_json = $po->terms()->get();
        $po->save();

        return $po;
    }
}