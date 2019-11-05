<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\JobOrderPutRequest;
use App\Models\JobOrder;
use App\Services\JobOrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JobOrdersController extends Controller
{
    protected $service;

    public function __construct(JobOrderService $service)
    {
        $this->service = $service;
    }

    public function show($id)
    {
        return JobOrder::with([
                'department',
                'location',
                'technicians',
            ])
            ->with(['items' => function($q) {
                $q->with('stock');
            }])
            ->where('id', $id)
            ->firstOrFail();
    }

    public function update(Request $request)
    {
        return $this->service->update($request->toArray());
    }

    public function complete($id)
    {
        return $this->service->complete($id);
    }

    public function search(Request $request)
    {
        $search = request()->input('q');
        return JobOrder::where('job_order_number', 'LIKE', '%'.$search.'%')->paginate(1000);
    }
}
