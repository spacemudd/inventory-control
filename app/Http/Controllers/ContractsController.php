<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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

        $contracts = Contract::query();

        if ($request->date_from) $contracts->where('issued_at', '>=', Carbon::parse($request->date_from));
        if ($request->date_to) $contracts->where('issued_at', '<=', Carbon::parse($request->date_to));

        $excel = Excel::create(now()->format('Y-m-d').'-contracts', function($excel) use ($contracts) {
            $excel->sheet('Sheet', function ($sheet) use ($contracts) {
                $sheet->appendRow([
                    'Contract No.',
                    'Cost Center',
                    'Supplier',
                    'Supplier Reference No.',
                    'Equipment',
                    'Location',
                    'Contract Start Date',
                    'Contract End Date',
                    'Contract Value',
                    'Total paid',
                    'Remaining',
                    'Remarks',
                ]);

                $contracts->each(function ($contract) use ($sheet) {
                    foreach ($contract->equipments()->get() as $equipment) {
                        $sheet->appendRow([
                            $contract->number,
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
                });
            });
        });

        return $excel->download('xlsx');
    }
}
