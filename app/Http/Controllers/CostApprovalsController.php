<?php

namespace App\Http\Controllers;

use App\CostApprovalLine;
use App\Models\CostApprovalQuotation;
use App\Models\Department;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use Illuminate\Http\Request;
use App\CostApproval;
use Carbon\Carbon;
use View;
use File;
use App\Models\MaxNumber;
use App\Models\Vendor;
use DB;

class CostApprovalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cas = CostApproval::query();
		
        
        //return request();
        
        if (request()->sort_by === 'number-desc') {
            $cas = $cas->orderBy('number', 'desc');
        } else {
        //return "hello";
         $cas->latest();
        }

        if (request()->has('cost_center_id') && request()->cost_center_id) {
            $cas = $cas->where('cost_center_id', request()->cost_center_id);
        }

        $cas =  $cas->paginate(100);

        $departments = Department::orderBy('code')->get();
		
        
        
        return view('cost-approvals.index', compact('cas', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Vendor::get();
        return view('cost-approvals.create', compact('vendors'));
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
          "requested_by_id" => "nullable|exists:employees,id",
          "cost_center_id" => "nullable|exists:departments,id",
          "project_location" => "nullable|string|max:255",
          "date" => "nullable",
          "purpose_of_request" => "nullable|string|max:255",
          "due_diligence_approved" => "nullable",
          "vendor_id" => "nullable|exists:vendors,id",
          "quotation_number" => "nullable|string|max:255",
          "quotation_numbers.*" =>  'nullable',
        ]);

        $ar = $request->toArray();
        $due = false;
        if (array_key_exists('due_diligence_approved', $ar))
        {
            if ($request->due_diligence_approved === 'false') {
                $due = false;
            }

            if ($request->due_diligence_approved === 'true') {
                $due = true;
            }
        }

        DB::beginTransaction();
        $ca = CostApproval::create([
            'requested_by_id' => $request->requested_by_id,
            'cost_center_id' => $request->cost_center_id,
            'project_location' => $request->project_location,
            'date' => $request->date ? Carbon::parse($request->date) : null,
            'purpose_of_request' => $request->purpose_of_request,
            'due_diligence_approved' => $due,
            'vendor_id' => $request->vendor_id,
            'quotation_number' => $request->quotation_number,
            'prepared_by_text' => json_encode([['name' => 'Ashraf Saeed', 'title' => 'Premises Centre']]),
            'approved_by_text' => json_encode([
                ['name' => 'Eng. Saleh N. Al Zunaidi', 'title' => 'Head of Premises and Administration Services'],
                ['name' => 'Fahad A. Alkadi', 'title' => 'Head of Retail Banking'],
                ]),
            ]);

        foreach ($request->quotation_numbers as $number) {
            $ca->adhoc_quotations()->save(new CostApprovalQuotation(['quotation_number' => $number]));
        }
        DB::commit();

        return redirect(route('cost-approvals.show', $ca->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ca = CostApproval::find($id);
        if (request()->wantsJson()) {
            return $ca;
        }
        return view('cost-approvals.show', compact('ca'));
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
        $request->validate([
            'approver_one_id' => 'nullable']);

        $ca = CostApproval::find($id);
        $ca->update($request->except(['_token', '_method']));
        if (request()->wantsJson()) {
            return $ca;
        }
        return redirect()->route('cost-approvals.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {  
    	$this->authorize('delete-cost-approvals');
    	
    	DB::beginTransaction();
    	
    	$caq = CostApprovalQuotation::where('cost_approval_id', $id);
    	$caq->delete();
    	
    	
        $ca = CostApproval::find($id);
        $ca->delete();
        
        DB::commit();
        
        return redirect()->route('cost-approvals.index');
    }

    public function print($id)
    {
        $data = CostApproval::find($id);
        $data->load('lines');

        $pdf = \App::make('snappy.pdf.wrapper');

        $pdf->setOption('page-size', 'A4');
        $pdf->setOption('orientation', 'portrait');
        $pdf->setOption('encoding', 'utf-8');
        $pdf->setOption('dpi', 300);
        $pdf->setOption('image-dpi', 300);
        $pdf->setOption('lowquality', false);
        $pdf->setOption('no-background', false);
        $pdf->setOption('enable-internal-links', true);
        $pdf->setOption('enable-external-links', true);
        $pdf->setOption('javascript-delay', 1000);
        $pdf->setOption('no-stop-slow-scripts', true);
        $pdf->setOption('no-background', false);
        // $pdf->setOption('margin-top', $marginTopDb ? $marginTopDb->value : 55);
        $pdf->setOption('margin-left', 15);
        $pdf->setOption('margin-right', 15);
        $pdf->setOption('margin-top', 97);
        $pdf->setOption('margin-bottom', 10);
        $pdf->setOption('disable-smart-shrinking', true);
        $pdf->setOption('zoom', 1);
        $pdf->setOption('header-html', $this->generateHeaderTempFile($data));
        //$pdf->setOption('footer-html', resource_path('views/pdf/footer.html'));

        return $pdf->loadView('pdf.cost-approvals.form', compact('data'))->inline();
    }

     /**
     *
     * @param $data
     * @return bool|string
     * @throws \Exception
     */
    public function generateHeaderTempFile($data)
    {
        $content = View::make('pdf.cost-approvals.header', compact('data'))
            ->render();

        // '@' to suppress an exception that tempnam throws when it creates a file.
        $fileLocation = @tempnam(sys_get_temp_dir(), 'cla');
        rename($fileLocation, $fileLocation .= '.html');
        str_replace('.tmp', '.html', $fileLocation);

        $writeAttempt = File::put($fileLocation, $content);

        if(! $writeAttempt) {
            throw new \Exception('Failed writing to: ' . $fileLocation);
        }

        return $fileLocation;
    }

    /**
    * 
    */
    public function save($id)
    {
        DB::beginTransaction();

        $ca = CostApproval::findOrFail($id);

        if($ca->number) return 'The cost approval has been already assigned a number. Please return to the homepage.';

        $numberPrefix = 'CA-'.Carbon::now()->format('Y');
        $maxNumber = MaxNumber::lockForUpdate()->firstOrCreate([
            'name' => $numberPrefix,
        ], [
            'value' => 0,
        ]);

        $number = ++$maxNumber->value;
        $maxNumber->save();

        $ca->number = 'CA/'.$ca->cost_center->code.'-'.$number.'/'.now()->format('Y');
        $ca->save();

        DB::commit();

        return redirect()->route('cost-approvals.show', ['id' => $ca->id]);
    }

    public function toPurchaseOrder($id)
    {
        $ca = CostApproval::find($id);

        \Illuminate\Support\Facades\DB::beginTransaction();
        $po = new PurchaseOrder();

        $po->vendor_id = $ca->vendor_id;
        $po->cost_center_id = $ca->cost_center_id;
        $po->date = now();
        $po->status = PurchaseOrder::NEW;
        $po->created_by_id = auth()->user()->id;
        $po->quote_reference_number = $ca->adhoc_quotations()->pluck('quotation_number')->implode('-');
        $po->cost_approval_id = $ca->id;
        $po->save();

        $poLines = [];
        foreach ($ca->lines as $line) {
            $poLine = new PurchaseOrderLine();
            $poLine->description = $line->description;
            $poLine->unit_price = $line->unit_price;
            $poLine->qty = $line->qty;
            $poLine->lump_sum = $line->lump_sum;
            $poLines[] = $poLine;
        }

        $po->lines()->saveMany($poLines);
        \Illuminate\Support\Facades\DB::commit();

        return redirect()->route('purchase-orders.show', ['id' => $po->id]);
    }
}
