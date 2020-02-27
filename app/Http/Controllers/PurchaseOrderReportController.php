<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseOrderReportController extends Controller
{
    public function index()
    {
        return view('purchase-orders-reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable',
            'date_to' => 'nullable',
        ]);

        $po = PurchaseOrder::query();

        if ($request->date_from) $po->where('date', '>=', Carbon::parse($request->date_from));
        if ($request->date_to) $po->where('date', '<=', Carbon::parse($request->date_to));

        $excel = Excel::create(now()->format('Y-m-d').'-purchase-orders', function($excel) use ($po) {
            $excel->sheet('Sheet', function ($sheet) use ($po) {
                $sheet->appendRow([
                    'P.O. No.',
                    'Vendor/Company',
                    'Location',
                    'Cost Code',
                    'Type of Work',
                    'Cost SR',
                    'VAT %5',
                    'Grand total',
                    'P.O. Date',
                    'Proceeding Date',
                    'Invoice Number',
                    'Tag#',
                    'Serial No.',
                    'Remarks',
                ]);

                $po->each(function ($purchaseOrder) use ($sheet) {
                    $sheet->appendRow([
                        $purchaseOrder->number,
                        $purchaseOrder->vendor->name,
                        $purchaseOrder->cost_center->description,
                        $purchaseOrder->cost_center->code,
                        $purchaseOrder->subject,
                        $purchaseOrder->lines()->sum('subtotal'),
                        $purchaseOrder->lines()->sum('vat'),
                        $purchaseOrder->lines()->sum('grand_total'),
                        $purchaseOrder->date->toDateString(),
                        optional(optional($purchaseOrder->supplier_invoice)->proceeded_date)->toDateString(),
                        optional($purchaseOrder->supplier_invoice)->number,
                        $purchaseOrder->lines()->pluck('tag_number')->implode('-'),
                        $purchaseOrder->lines()->pluck('serial_number')->implode('-'),
                        $purchaseOrder->remarks,
                    ]);
                });
            });
        });

        return $excel->download('csv');
    }
}
