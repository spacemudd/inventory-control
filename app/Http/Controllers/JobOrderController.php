<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Services\JobOrderService;
use App\Http\Requests\JobOrderRequest;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class JobOrderController extends Controller
{
    protected $service;

    private $stockService;

    public function __construct(JobOrderService $service, StockService $stockService)
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
            $jobOrders = JobOrder::latest()->paginate(1000);
        } elseif (request()->has('completed')) {
            $jobOrders = JobOrder::completed()->paginate(1000);
        } else {
            $jobOrders = JobOrder::pending()->latest()->paginate(100);
        }
        return view('job-orders.index', compact('jobOrders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('job-orders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\JobOrderRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobOrderRequest $request)
    {
        $jobOrder = DB::transaction(function() use ($request) {
            $jobOrder = $this->service->save($request);
            $this->service->addTechniciansTo($jobOrder, $request->technicians);
            $this->service->addMaterialsUsed($jobOrder, $request->materials);
            return $jobOrder;
        });

        if (request()->expectsJson()) {
            return $jobOrder;
        }

        return redirect()->route('job-orders.index');
    }

    /**
     * Display the specified resource.
     *
     * @param JobOrder $jobOrder
     * @return \Illuminate\Http\Response
     */
    public function show(JobOrder $jobOrder)
    {
        $jobOrder->load('technicians', 'items.stock', 'items.technician');
        
        return view('job-orders.show', compact('jobOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JobOrder  $jobOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(JobOrder $jobOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\JobOrderRequest  $request
     * @param  \App\JobOrder  $jobOrder
     * @return \Illuminate\Http\Response
     */
    public function update(JobOrderRequest $request, JobOrder $jobOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JobOrder  $jobOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->service->destroy($id);
        return [
            'redirect' => route('job-orders.index'),
        ];
    }

    /**
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function streamPdf($id)
    {
        $jobOrder = JobOrder::where('job_order_number', $id)->firstOrFail();
        return $this->service->streamPdf($jobOrder);
    }

    /**
     * Approve Job Order
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $jobOrder = JobOrder::where('job_order_number', $id)->firstOrFail();

        $this->service->approve($jobOrder);

        return back();
    }


    /**
     * Dispatch job order item
     *
     * @param $jobOrder
     * @param $jobOrderItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function dispatchItem($jobOrder, $jobOrderItem)
    {
        $jobOrder = JobOrder::where('job_order_number', $jobOrder)->firstOrFail();

        $this->service->dispatchItem($jobOrder, $jobOrderItem);

        return back();
    }
}
