<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PurchaseOrderReportController extends Controller
{
    public function index()
    {
        return view('purchase-orders-reports.index');
    }
    
    public function PurchaseOrdersIssuedToSuppliersParams()
    {
    	
    	
    	return view('purchase-orders-reports.supplier-params');
    }

    
    
       
    public function generateReport(Request $request)
    {
    	if($request->is_group)
    	{
    		self::generateIssuedSupplierGrouped($request);
    	}
    	
    	else
    	{
    		self::generateIssuedSupplier($request);
    	}
    	
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
                    'Void',
                    'Proceeding Date',
                    'Invoice Number',
                    'Tag#',
                    'Serial No.',
                    'Remarks',
                	'Created by',
                ]);

                $po->each(function ($purchaseOrder) use ($sheet) {

                    $voidedBy = ' - '.optional($purchaseOrder->voided_by)->display_name;

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
                        $purchaseOrder->status_name === 'void' ? 'VOID'.$voidedBy : '',
                        optional(optional($purchaseOrder->supplier_invoice)->proceeded_date)->toDateString(),
                        optional($purchaseOrder->supplier_invoice)->number,
                        $purchaseOrder->lines()->pluck('tag_number')->implode('-'),
                        $purchaseOrder->lines()->pluck('serial_number')->implode('-'),
                        $purchaseOrder->remarks,
                        optional($purchaseOrder->created_by)->display_name,
                    ]);
                });
            });
        });

        return $excel->download('xlsx');
    }
    
    private static function generateIssuedSupplier(Request $request)
    {
    	
    	$request->validate([
    			'date_from' => 'required',
    			'date_to' => 'required',
    			'vendor_id' => 'required'
    	]);
    	
    	$po = PurchaseOrder::query()
    	->select('purchase_orders.*')
    	->join('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
    	->where('date', '>=', Carbon::parse($request->date_from))
    	->where('date', '<=', Carbon::parse($request->date_to))
    	->where('vendor_id', $request->vendor_id);
    	;
    	
    	$excel = Excel::create(now()->format('Y-m-d').'-purchase-orders-suppliers', function($excel) use ($po) {
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
    					'Void',
    					'Proceeding Date',
    					'Invoice Number',
    					'Tag#',
    					'Serial No.',
    					'Remarks',
    					'Created by',
    			]);
    			
				$summary['subtotal'] = 0;
				$summary['vat'] = 0;
				$summary['grand_total'] = 0;
				$po->each(function ($purchaseOrder) use ($sheet, &$summary) {
    				
    				
    				$voidedBy = ' - '.optional($purchaseOrder->voided_by)->display_name;
    				
    				$subtotal = $purchaseOrder->lines()->sum('subtotal');
    				$vat = $purchaseOrder->lines()->sum('vat');
    				$grand_total = $purchaseOrder->lines()->sum('grand_total');
    				$sheet->appendRow([
    						$purchaseOrder->number,
    						$purchaseOrder->vendor->name,
    						$purchaseOrder->cost_center->description,
    						$purchaseOrder->cost_center->code,
    						$purchaseOrder->subject,
    						$subtotal,
    						$vat,
    						$grand_total,
    						$purchaseOrder->date->toDateString(),
    						$purchaseOrder->status_name === 'void' ? 'VOID'.$voidedBy : '',
    						optional(optional($purchaseOrder->supplier_invoice)->proceeded_date)->toDateString(),
    						optional($purchaseOrder->supplier_invoice)->number,
    						$purchaseOrder->lines()->pluck('tag_number')->implode('-'),
    						$purchaseOrder->lines()->pluck('serial_number')->implode('-'),
    						$purchaseOrder->remarks,
    						optional($purchaseOrder->created_by)->display_name,
    				]);
    				
    				$summary['subtotal'] += $subtotal;
    				$summary['vat'] += $vat;
    				$summary['grand_total'] += $grand_total;
    				
    			});
    				
    				$sheet->appendRow([
    						'Total ',
    						$po->count(),
    						'',
    						'',
    						'',
    						$summary['subtotal'],
    						$summary['vat'],
    						$summary['grand_total']
    						
    				]);
    				
    		});
    	});
    		
    		return $excel->download('xlsx');
    	
    }
    
    private static function generateIssuedSupplierGrouped(Request $request)
    {
    	$request->validate([
    			'date_from' => 'nullable',
    			'date_to' => 'nullable',
    	]);
    	
    	/*$po = PurchaseOrder::query();
    	$po->select(DB::raw('vendor_id, count(*) as user_count, sum(subtotal) as subtotal, sum(vat) as vat, sum(grand_total) as grand_totalz'));
    	//->select('purchase_orders.*')
    	$po->join('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id');
    	$po->join('purchase_order_lines', 'purchase_orders.id', 'purchase_order_lines.purchase_order_id');
    	
    	if ($request->date_from) $po->where('date', '>=', Carbon::parse($request->date_from));
    	if ($request->date_to) $po->where('date', '<=', Carbon::parse($request->date_to));
    	
    	$po->whereNotNull('vendor_id');
    	$po->groupBy('vendor_id');
    	$po->orderBy('vendor_id');*/
    	
    	$dateFrom = date_create($request->date_from);
    	$dateTo =   date_create($request->date_to); 
    	
    	
    	
    	
    	
    	$po = DB::select(DB::raw("select vendor_id, name, count(*)as user_count, sum(subtotal) as subtotal, sum(vat) as vat, sum(grand_total) as grand_total from
									(
									
								    select *, 
									isnull((select sum(subtotal) from inv_purchase_order_lines where purchase_order_id = po.id), 0) as subtotal,
									isnull((select sum(vat) from inv_purchase_order_lines where purchase_order_id = po.id), 0) as vat,
									isnull((select sum(grand_total) from inv_purchase_order_lines where purchase_order_id = po.id), 0) as grand_total
									
									from inv_purchase_orders po
								
									where date>='".$request->date_from."' and date<='".$request->date_to."' 
									
									)ddas
									inner join inv_vendors on ddas.vendor_id = inv_vendors.id
									group by vendor_id, inv_vendors.name"
    						    )
    					 );
    	
    	
    	
    	
    	$excel = Excel::create(now()->format('Y-m-d').'-purchase-orders-suppliers-grouped', function($excel) use ($po, $dateFrom, $dateTo){
    		$excel->sheet('Sheet', function ($sheet) use ($po, $dateFrom, $dateTo) {
    			
    			
    			
    			$sheet->appendRow([
    					'from '.date_format($dateFrom, 'Y/m/d').' to '.date_format($dateTo, 'Y/m/d')
    			]);
    			
    			$sheet->appendRow([
    					'Vendor/Company',
    					'Count',
    					'Total'
    			]);
    			
    			foreach ($po as $purchaseOrder)
    			{
    				$sheet->appendRow([
    						$purchaseOrder->name,
    						$purchaseOrder->user_count,
    						$purchaseOrder->grand_total
    				]);
    			}
    			
    			
    	
    				
    				
    				
    		});
    	});
    		
    		return $excel->download('xlsx');
    	
    }
    
    
    
    
    
    
}
