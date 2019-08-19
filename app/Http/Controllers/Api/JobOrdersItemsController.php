<?php

namespace App\Http\Controllers\Api;

use App\Models\JobOrder;
use App\Models\JobOrderItem;
use App\Services\JobOrderService;
use App\Services\StockService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JobOrdersItemsController extends Controller
{
    protected $service;

    protected $stockService;

    public function __construct(JobOrderService $service, StockService $stockService)
    {
        $this->service = $service;
        $this->stockService = $stockService;
    }

    public function destroy($id)
    {
        $item = JobOrderItem::find($id);

        if ($item->jobOrder->isCompleted()) {
            throw new \Exception('Cant delete item of a completed job order');
        }

        if ($item->dispatched_at) {
            $this->stockService->addIn($item->stock->description, $item->qty);
        }

        $item->delete();

        return [
            'msg' => 'success',
        ];
    }

    public function store(Request $request)
    {
        // todo: validation.
        $request->validate([
            'job_order_id' => 'required|exists:job_orders,id',
            'materials' => 'required',
        ]);

        $jo = JobOrder::find($request->job_order_id);
        $this->service->addMaterialsUsed($jo, $request->materials);
        $jo = JobOrder::with('items')->findOrFail($request->job_order_id);
        return $jo;
    }

    public function dispatchItem(Request $request)
    {
        $request->validate([
            'job_order_item_id' => 'required|exists:job_order_items,id',
        ]);

        $item = JobOrderItem::find($request->job_order_item_id);
        // todo: make sure the qty is available...
        return $this->service->dispatchItem($item->jobOrder, $item->id);
    }
}
