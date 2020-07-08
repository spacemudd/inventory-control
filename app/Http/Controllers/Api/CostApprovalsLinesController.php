<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CostApprovalLine;
use App\Models\SupplierInvoice;

class CostApprovalsLinesController extends Controller
{
    public function index($cost_approval_id)
    {
    	return CostApprovalLine::where('cost_approval_id', $cost_approval_id)->get();
    }

    public function store($cost_approval_id, Request $request)
    {
    	$request->validate([
    		'cost_approval_id' => 'required|exists:cost_approvals,id',
    		'description' => 'required|string|max:800',
    		'unit_price' => 'required',
    		'qty' => 'required',
            'quotation_number' => 'required',
    		]);

    	$line = $request->toArray();

    	if ($line['lump_sum']) {
    	    $line['qty'] = 1;
        }

        $line = CostApprovalLine::create($line);

        return $line;
    }
    
    /*borrowed this controller to save invoice no. update, will transfer later.*/
    public function storeinvoiceupdate($invoice_id, Request $request)
    {
    	try {
    		$request->validate([
    				'number' => 'required'
    		]);
    		
    		$value = $request->toArray();
    		
    		$line = SupplierInvoice::find($invoice_id);
    		$line->number = $value['number'];
    		$line->save();
    		
    		return [
    			'status'=>'saved',
    			'message'=>''
    		];
    	} catch (\Exception $e) {
    		return [
    			'status'=>'failed',
    			'message'=>$e->getMessage()
    		];
    	}
    	
    }
    

    public function delete($cost_approval_id, $id)
    {
        $line = CostApprovalLine::find($id);
        $line->delete();
        return response()->json(['sucess' => true]);
    }
}
