<?php

namespace App\Http\Controllers\Api;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseOrderLinesController extends Controller
{
    public function index($purchaseOrderId)
    {
        return PurchaseOrderLine::where('purchase_order_id', $purchaseOrderId)->get();
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return PurchaseOrderLine
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'description' => 'required|string|max:255',
            'unit_price' => 'required|numeric',
            'quantity' => 'nullable|numeric',
        	'quote_no' => 'required'
        ]);

        $line = $request->toArray();
        $line['subtotal'] = $request['lump_sum'] ? $request['unit_price'] : round($request['unit_price']*$request['qty'], 2);

        $oldVat = 0.05;
        $newSaudiVat = 0.15;

        $d = now()->setDate(2020, 6, 30)->startOfDay();

        if (now()->greaterThan($d)) {
            $line['vat'] = round($line['subtotal'] * $newSaudiVat,2);
        } else {
            $line['vat'] = round($line['subtotal'] * $oldVat,2);
        }

        $line['grand_total'] = $line['subtotal']+$line['vat'];

        return PurchaseOrderLine::create($line);
    }

    public function destroy($poId, $itemId)
    {
        PurchaseOrderLine::whereId($itemId)->delete();
        return response()->json([
            'success' => true,
        ]);
    }

    public function update(Request $request, $poId, $lineId)
    {
        $request->validate([
            'serial_number' => 'nullable|string|max:255',
            'tag_number' => 'nullable|string|max:255',
        ]);

        $po = PurchaseOrderLine::find($lineId);
        $po->update($request->only(['serial_number', 'tag_number']));
        $po->save();

        return $po;
    }
}
