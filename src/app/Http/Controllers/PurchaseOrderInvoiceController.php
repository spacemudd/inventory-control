<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\SupplierInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseOrderInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($poId)
    {
        $po = PurchaseOrder::find($poId);
        return view('purchase-orders-invoices.create', compact('po'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'number' => 'required|string|max:255',
            'proceeded_date' => 'required|string|max:255',
            'lines.*.serial_number' => 'nullable|string|max:255',
            'lines.*.tag_number' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        // Process all serial and tag numbers.
        foreach ($request->lines as $line) {
            PurchaseOrderLine::whereId($line['id'])
                ->update([
                    'serial_number' => $line['serial_number'],
                    'tag_number' => $line['tag_number'],
                ]);
        }

        $supplierInvoice = SupplierInvoice::create([
            'purchase_order_id' => $request['purchase_order_id'],
            'proceeded_date' => Carbon::parse($request['proceeded_date']),
            'number' => $request['number'],
            'vendor_id' => PurchaseOrder::find($request['purchase_order_id'])->vendor_id,
        ]);
        DB::commit();

        return redirect()->route('purchase-orders.show', ['id' => $request['purchase_order_id']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
