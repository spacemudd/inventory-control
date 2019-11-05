<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Services\JobOrderService;
use App\Http\Requests\JobOrderRequest;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
            $jobOrders = JobOrder::latest()->paginate(100);
        } elseif (request()->has('completed')) {
            $jobOrders = JobOrder::completed()->latest()->paginate(100);
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
     * @param \App\Models\JobOrder $jobOrder
     * @return void
     */
    public function edit(JobOrder $jobOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\JobOrderRequest $request
     * @param \App\Models\JobOrder $jobOrder
     * @return void
     */
    public function update(JobOrderRequest $request, JobOrder $jobOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return array
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
     * @throws \Exception
     */
    public function dispatchItem($jobOrder, $jobOrderItem)
    {
        $jobOrder = JobOrder::where('job_order_number', $jobOrder)->firstOrFail();

        $this->service->dispatchItem($jobOrder, $jobOrderItem);

        return back();
    }

    /**
     * Downloads an Excel list of all the job orders items.
     *
     */
    public function excel()
    {
        $excel = Excel::create(now()->format('Y-m-d').'-job-orders', function($excel) {
            $excel->sheet('Sheet', function ($sheet) {
                $sheet->appendRow([
                    'Number',
                    'Date',
                    'Status',
                    'Job description',
                    'Location',
                    'Requested through',
                    'Requester',
                    'Cost Center',
                    'Remark',
                    'Start Time',
                    'End Time',
                    'Material name',
                    'Material qty',
                    'Technician',
                    'Technician Time Start',
                    'Technician Time End',
                ]);

                JobOrder::each(function ($jO) use ($sheet) {
                    $sheet->appendRow([
                        $jO->job_order_number,
                        $jO->date->format('Y-m-d'),
                        $jO->status,
                        $jO->job_description,
                        optional($jO->location)->name,
                        $jO->requested_through_type,
                        optional($jO->employee)->code,
                        optional($jO->department)->display_name,
                        $jO->remark,
                        $jO->time_start,
                        $jO->time_end,
                    ]);

                    // Add items.
                    $jO->items()->each(function ($item) use ($sheet, $jO) {
                        $sheet->appendRow([
                            $jO->job_order_number,
                            $jO->date->format('Y-m-d'),
                            $jO->status,
                            $jO->job_description,
                            optional($jO->location)->name,
                            $jO->requested_through_type,
                            optional($jO->employee)->code,
                            optional($jO->department)->display_name,
                            $jO->remark,
                            $jO->time_start,
                            $jO->time_end,
                            optional($item->stock)->description,
                            $item->qty,
                        ]);

                        // Add technicians.
                        $jO->technicians()->each(function ($tech) use ($sheet, $jO) {
                            $sheet->appendRow([
                                $jO->job_order_number,
                                $jO->date->format('Y-m-d'),
                                $jO->status,
                                $jO->job_description,
                                optional($jO->location)->name,
                                $jO->requested_through_type,
                                optional($jO->employee)->code,
                                optional($jO->department)->display_name,
                                $jO->remark,
                                $jO->time_start,
                                $jO->time_end,
                                '',
                                '',
                                $tech->display_name,
                                $tech->time_start,
                                $tech->time_end,
                            ]);
                        }, 100);
                    }, 50);
                });
            });
        });

        return $excel->download('csv');
    }
}
