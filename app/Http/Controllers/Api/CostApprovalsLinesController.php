<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CostApprovalLine;

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
    		'description' => 'required|string|max:255',
    		'unit_price' => 'required',
    		'qty' => 'required',
    		]);

    	$line = $request->toArray();

    	if ($line['lump_sum']) {
    	    $line['qty'] = 1;
        }

        $line = CostApprovalLine::create($line);

        return $line;
    }

    public function delete($cost_approval_id, $id)
    {
        $line = CostApprovalLine::find($id);
        $line->delete();
        return response()->json(['sucess' => true]);
    }
}
