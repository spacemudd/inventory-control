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
        $jobOrders = JobOrder::paginate(15);
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
        DB::transaction(function() use ($request) {
            $jobOrder = $this->service->save($request);
            $this->service->addTechniciansTo($jobOrder, $request->technicians);
        });

        return redirect()->route('job-orders.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JobOrder  $jobOrder
     * @return \Illuminate\Http\Response
     */
    public function show(JobOrder $jobOrder)
    {
        $jobOrder->load('technicians');
        
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
    public function destroy(JobOrder $jobOrder)
    {
        //
    }

    /**
     *
     * @param $id
     * @return mixed
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
     */
    public function approve($id)
    {
        $jobOrder = JobOrder::where('job_order_number', $id)->firstOrFail();

        $this->service->approve($jobOrder);

        return back();
    }
}