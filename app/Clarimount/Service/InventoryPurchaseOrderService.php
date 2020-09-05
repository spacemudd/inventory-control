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
        
        $poRequest = $request->except('_token');
        $poRequest['status'] = PurchaseOrder::APPROVED;
        $poRequest['approver_one_id'] = optional(Employee::where('name', 'LIKE', '%Saleh N. Al-Zunaidi%')->first())->id;
        $poRequest['approver_two_id'] = optional(Employee::where('name', 'LIKE', 'Ashraf Saeed')->first())->id;
        $poRequest['created_by_id'] = auth()->user()->id;

        return PurchaseOrder::create($poRequest);
    }
}
