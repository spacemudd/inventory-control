<?php

namespace App\Http\Controllers;

use App\Models\SupplierInvoice;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupplierInvoicesController extends Controller
{
    public function index()
    {    	
    	$invoices = SupplierInvoice::all();
    	
    	return view('supplier-invoices.index', compact('invoices'));  
    }

    public function create()
    {
    	$vendors = Vendor::get();
    	return view('supplier-invoices.create', compact('vendors'));
    }
    
    public function show($id)
    {
    	//return "bokya ".$id;
    	/*return $this->model->where('id', $id)
    	->with([
    			'items',
    			'vendor',
    			'files',
    			'department',
    			'employee',
    			'project',
    	])
    	->firstOrFail(); */
    	$invoice = SupplierInvoice::where('id', $id)->firstOrFail();
    	
    	return view('supplier-invoices.show', compact('invoice'));
    	
    }

    public function store(Request $request)
    {
    	$request->validate([
    		'po_number' => 'required',
    		'vendor_id' => 'required|exists:vendors,id',
    		'proceeded_date' => 'required',
    		'number' => 'required',
    		'remarks' => 'nullable',
    		]);

    	$date = Carbon::parse($request->proceeded_date);

    	SupplierInvoice::create([
    		'po_number' => $request->po_number,
    		'vendor_id' => $request->vendor_id,
    		'proceeded_date' => $date,
    		'number' => $request->number,
    		'remarks' => $request->remarks,
    		]);
    	//return redirect()->route('invoices.show');
    	return redirect()->route('supplier-invoices.index');
    	
    }
    
    public function destroy($id)
    {
    	$invoice = SupplierInvoice::find($id);
    	$invoice->delete();
    	
    	return redirect()->route('supplier-invoices.index');
    }
}
