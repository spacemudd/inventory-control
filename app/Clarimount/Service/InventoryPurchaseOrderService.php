<?php

namespace App\Clarimount\Service;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

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
        $poRequest['status'] = PurchaseOrder::NEW;
        $poRequest['created_by_id'] = auth()->user()->id;

        return PurchaseOrder::create($poRequest);
    }
}
