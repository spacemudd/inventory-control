<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Location;
use App\Models\MaxNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$contracts = Contract::paginate(100);
        if(request()->sort_by=='contract-id-asc')
            $contracts = Contract::query()->orderBy('number', 'asc')->paginate(100);
        else if(request()->sort_by=='contract-id-desc')
            $contracts = Contract::query()->orderBy('number', 'desc')->paginate(100);
        else if(request()->sort_by=='date-desc')
            $contracts = Contract::query()->orderBy('issued_at', 'desc')->paginate(100);
        else if(request()->sort_by=='date-asc')
            $contracts = Contract::query()->orderBy('issued_at', 'asc')->paginate(100);
        else if(request()->sort_by=='supplier-desc')
            $contracts = Contract::sortBySupplier('desc')->paginate(100);
        else if(request()->sort_by=='supplier-asc')
            $contracts = Contract::sortBySupplier('asc')->paginate(100);
        else if(request()->sort_by=='cost-desc')
            $contracts = Contract::query()->orderBy('cost', 'desc')->paginate(100);
        else if(request()->sort_by=='cost-asc')
            $contracts = Contract::query()->orderBy('cost', 'asc')->paginate(100);
        else if(request()->sort_by=='value-desc')
            $contracts = Contract::query()->orderBy('total_cost', 'desc')->paginate(100);
        else if(request()->sort_by=='value-asc')
            $contracts = Contract::query()->orderBy('total_cost', 'asc')->paginate(100);
        else if(request()->sort_by=='paid-asc')
            $contracts = Contract::sortByPaid('asc')->paginate(100);
        else if(request()->sort_by=='paid_desc')
            $contracts = Contract::sortByPaid('desc')->paginate(100);
        else
            $contracts = Contract::paginate(100);

        return view('contracts.index', compact('contracts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contracts.create');
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
            'issued_at' => 'required|string',
            'expires_at' => 'required|string',
            'vendor_id' => 'required|exists:vendors,id',
            'vendor_reference' => 'required|string',
            'cost_center_id' => 'required|exists:departments,id',
            'remarks' => 'nullable|string|max:1000',
            'cost' => 'required',
            'payment_frequency' => 'required',
        ]);
        
        $request->offsetSet('automatic_renewal', $request->input('automatic_renewal')!==null ? 1 : 0);
        	
      
 

        $contract = Contract::create($request->except('_token'));

        return redirect()->route('contracts.show', $contract->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contract = Contract::findOrFail($id);
        return view('contracts.show', compact('contract'));
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

    public function export()
    {
        return view('contracts.export');
    }

    public function excel(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable',
            'date_to' => 'nullable',
        ]);

        $contracts = Contract::whereHas('payments', function($q) use ($request) {
            $q->whereBetween('proceeded_date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)]);
        })->get();

        $excel = Excel::create(now()->format('Y-m-d').'-contracts', function($excel) use ($contracts, $request) {
            $excel->sheet('Sheet', function ($sheet) use ($contracts, $request) {

                $sheet->appendRow([
                    'Selected Period:',
                    Carbon::parse($request->date_from)->format('d-m-Y').' to '.Carbon::parse($request->date_to)->format('d-m-Y'),
                ]);

                $sheet->appendRow(['']);
                $sheet->appendRow(['']);

                $row = 0;
                foreach ($contracts as $contract)
                {
                	$sheet->appendRow([
                			'Contract No.',
                			'Cost Center',
                			'Supplier',
                			'Supplier Reference No.',
                			'Equipment',
                			'Location',
                			'Contract Start Date',
                			'Contract End Date',
                			'Auto Renewal',
                			'Contract Value',
                			'Total paid',
                			'Remaining',
                			'Remarks',
                	]);
                	$row++;
                	
                	$sheet->appendRow([
                			$contract->number,//$contract->number,
                			optional($contract->cost_center)->display_name,
                			optional($contract->vendor)->display_name,
                			$contract->vendor_reference_number,
                			'', // equipment
                			'', // optional(Location::find($equipment->pivot->location_id))->name,
                			$contract->issued_at,
                			$contract->expires_at,
                			$contract->auto_renewal ? 'Yes' : 'No',
                			$contract->total_cost,
                			$contract->payments()->sum('cost'),
                			$contract->total_cost - $contract->payments()->sum('cost'),
                			$contract->remarks,
                	]);
                	$row++;
                	
                	
                	$sheet->appendRow([
                			"Payments of Contract No.: ".$contract->number
                	]);
                	
                	$row++;
                	
                	$sheet->mergeCells('A'.$row.':E'.$row);
                	
                	$sheet->appendRow([
                			'Invoice Period',
                			'Proceeded Amount',
                			'Proceeded Date',
                			'Invoice No',
                			'Tax Invoice Amount'
                	]);
                	$row++;
                	
                	
                	foreach ($contract->payments()->get() as $payment)
                	{
                		$sheet->appendRow([
                				$payment->invoice_period_from." to ".$payment->invoice_period_to,
                				$payment->cost,
                				$payment->proceeded_date,
                				$payment->invoice_no,
                				$payment->invoice_tax_amount
                		]);
                		$row++;
                	}
                	
                	$sheet->appendRow([
                			'<<<< End of line - Nothing follows >>>>'
                	]);
                	$row++;
                	
                	$sheet->appendRow([
                			''
                	]);
                	$row++;
                }

              /*  $contracts->each(function ($contract) use ($sheet) {
                    foreach ($contract->equipments()->get() as $equipment) {
                        $sheet->appendRow([
                            'ningpayat',//$contract->number,
                            optional($contract->cost_center)->display_name,
                            optional($contract->vendor)->display_name,
                            $contract->vendor_reference_number,
                            $equipment->name,
                            optional(Location::find($equipment->pivot->location_id))->name,
                            $contract->issued_at,
                            $contract->expires_at,
                            $contract->total_cost,
                            $contract->payments()->sum('cost'),
                            $contract->total_cost - $contract->payments()->sum('cost'),
                            $contract->remarks,
                        ]);
                    }
                }); */
            });
        });

        return $excel->download('xlsx');
    }

    public function save($id)
    {
        $contract = Contract::findOrFail($id);

        DB::beginTransaction();

        if (!$contract->number) {
            $numberPrefix = 'CO-'.Carbon::now()->format('Y');
            $maxNumber = MaxNumber::lockForUpdate()->firstOrCreate([
                'name' => $numberPrefix,
            ], [
                'value' => 0,
            ]);
            $number = ++$maxNumber->value;
            $maxNumber->save();

            $contract->number = 'CO/'.$number.'/'.now()->format('Y');
        }
        $contract->status = Contract::STATUS_SAVED;
        $contract->save();

        DB::commit();

        return redirect()->route('contracts.show', $id);
    }

    public function search()
    {
        $search = request()->input('q');

        $contracts = Contract::where('number', 'LIKE', '%' . $search . '%')
            ->orWhere('vendor.name', 'LIKE', '%' . $search . '%');

        return $contracts->paginate(15);
    }
}
