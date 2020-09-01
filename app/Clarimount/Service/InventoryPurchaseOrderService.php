<?php

namespace App\Clarimount\Service;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Models\Employee;

class InventoryPurchaseOrderService
{
    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'nullable|string|max:255',
            'cost_center_id' => 'required|exists:departments,id',
            'date' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'vendor_id' => 'required|exists:vendors,id',
            'quote_reference_number' => 'required|string|max:255',
        ]);
        
       /* $approvers = Employee::where('approver', true)
        ->andWhere('name', 'LIKE', '%' . $search . '%')
        ->orWhere('name', 'LIKE', '%' . $search . '%')
        ->get(); */
        
        $approvers = Employee::where(function ($query) {
        	$query->where('approver', true);        	
        })->where(function ($query) {
        	$query->where('name', 'LIKE', 'Ashraf%')
        	->orWhere('name', 'LIKE', '%Saleh%');
        })->get();
        

        $poRequest = $request->except('_token');
        $poRequest['status'] = PurchaseOrder::NEW;
        $poRequest['approver_one_id'] = $approvers[0]->id;
        $poRequest['approver_two_id'] = $approvers[1]->id;
        $poRequest['created_by_id'] = auth()->user()->id;
        
        
        
        
        //$po = PurchaseOrder::create($poRequest);

        return PurchaseOrder::create($poRequest);
    }
}
