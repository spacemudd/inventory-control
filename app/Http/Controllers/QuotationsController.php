<?php

namespace App\Http\Controllers;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use App\Models\Quotation;
use App\Models\Region;
use App\Models\Vendor;
use App\Services\QuotationsService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class QuotationsController extends Controller
{
    protected $service;

    protected $stockService;

    public function __construct(QuotationsService $service, StockService $stockService)
    {
        $this->service = $service;
        $this->stockService = $stockService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (request()->has('all')) {
            $quotations = Quotation::get();
        } elseif (request()->has('saved')) {
            $quotations = Quotation::savedQuotations()->get();
        } elseif (request()->has('draft')) {
            $quotations = Quotation::draft()->get();
        } else {
            $quotations = Quotation::draft()->get();
        }


        return view('quotations.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Vendor::get();
        $mRequests = MaterialRequest::get();
        $regions = Region::get();
        return view('quotations.create', compact('vendors', 'mRequests', 'regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'material_request_id' => 'required|numeric|exists:material_requests,id',
            'vendor_id' => 'required|numeric|exists:vendors,id',
            'vendor_quotation_number' => 'required|string|max:255',
            //'region_id' => 'required|numeric|exists:regions,id'
        ]);

        // TODO: Confirm unique on vendor_id->vendor_quotation_number

        $request['status'] = Quotation::DRAFT;
        $request['cost_center_id'] = MaterialRequest::find($request['material_request_id'])->cost_center_id;
        $quotation = Quotation::create($request->except('_token'));

        return redirect()->route('quotations.show', ['id' => $quotation->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quotation = Quotation::with('items')->where('id', $id)->firstOrFail();
        $mRequest = MaterialRequest::with('items')->where('id', $quotation->material_request_id)->first();
        return view('quotations.show', compact('quotation', 'mRequest'));
    }

    public function changeStatus($mRequestId)
    {
        return $mRequest = MaterialRequestItem::where('id', $mRequestId)
            ->with('last_template')
            ->with('last_quoted')
            ->with('stock_template')
            ->first();

        return json_encode($mRequest);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quotation = Quotation::with('material_request')->find($id);
        $mRequests = MaterialRequest::open()->get();
        return view('quotations.edit', compact('quotation', 'mRequests'));
   }

    public function update(Request $request, $id)
    {
        $request->validate([
            'material_request_id' => 'required|numeric|exists:material_requests,id',
            'vendor_id' => 'required|numeric|exists:vendors,id',
            'vendor_quotation_number' => 'required|string|max:255',
            //'region_id' => 'required|numeric|exists:regions,id'
        ]);

        $quotation = Quotation::findOrFail($id);
        $quotation->update($request->except('_token'));

        return redirect()->route('quotations.show', ['id' => $id]);
    }

    /**
     * Deletes a quote.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $quotation = Quotation::find($id);

        DB::beginTransaction();

        // Stock count must be readjusted.
        if ($quotation->status === Quotation::SAVED) {
            foreach ($quotation->items as $item) {
                Log::info('Deleting quotation: Moving '.$item->description.' out by: '.$item->qty);
                $this->stockService->moveOut($item->description, $item->qty, $item);
            }

            // Supplier's journal must be readjusted.
            $journal = $quotation->vendor->journal;
            if (!$journal) {
                $quotation->vendor->initJournal('SAR');
                $quotation->vendor->refresh();
            }
            $transaction = $quotation->vendor->journal->debitDollars($quotation->items()->sum('total_price_inc_vat'));
            $transaction->referencesObject($quotation);
        }

        // Set delete name
        $quotation->vendor_quotation_number .= str_replace(' ', '', 'deleted-'.now()->toDateTimeString());

        $quotation->items()->delete();
        $quotation->delete();

        // Make its material request pending
        $mr = $quotation->material_request;
        if ($mr) {
            $mr->status = MaterialRequest::PENDING;
            $mr->save();
        }

        DB::commit();

        return redirect()->route('quotations.index');
    }

    /**
     * Change status from 'DRAFT' to 'SAVED.
     * No more changes shall be done to the quotations from now on.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save($id)
    {
        $quote = $this->service->save($id);
        return redirect()->route('quotations.show', ['id' => $quote->id]);
    }
}
