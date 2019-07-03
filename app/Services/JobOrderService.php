<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;
use App\Models\JobOrder;
use Illuminate\Http\Request;
use App\Models\Employee;

class JobOrderService
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }
    /**
     * Create job order
     *
     * @param Request $request
     * @return JobOrder
     */
    public function save(Request $request)
    {
        if($request->employee_id == null && $request->employeeName) {
            $toInsert = [
                'code' => $request->employeeName,
                'department_id' => null,
                'staff_type_id' => null,
                'name' => $request->employeeName,
                'email' => null,
                'phone' => null,
                'approver' => false,
            ];

            $request['employee_id'] = Employee::insertGetId($toInsert);
        }

        $jobData = array_merge([
            'date' => date('Y-m-d', strtotime($request->date)),
            'job_order_number' => $this->generateJobNumber()
        ], $request->only(
            'employee_id',
            'cost_center_id',
            'ext',
            'requested_through_type',
            'job_description',
            'status',
            'remark',
            'location_id',
            'time_start',
            'time_end',
            'quotation_id',
            'region_id'
        ));

        $jobData['status'] = 'draft';

        $jobOrder = JobOrder::create($jobData);

        return $jobOrder;
    }

    
    /**
     * Generate unique job number
     *
     * @return string
     */
    public function generateJobNumber()
    {
        if (env('START_JOB_ORDERS_NUMBER_FROM')) {
            return env('START_JOB_ORDERS_NUMBER_FROM') + optional(JobOrder::latest()->first())->id;
        }

        return (1000) + optional(JobOrder::latest()->first())->id;
    }


    /**
     * Sync technicians to job order
     *
     * @param JobOrder $jobOrder
     * @param array $technicians
     * @return array
     */
    public function addTechniciansTo(JobOrder $jobOrder, $technicians)
    {
        $newArray = [];
        foreach ($technicians as &$tech) {
            if ($tech['time_start']) {
                $tech['time_start'] = Carbon::parse($tech['time_start']);
                $tech['time_start'] = $tech['time_start']->format('H:i:s');
            }
            if ($tech['time_end']) {
                $tech['time_end'] = Carbon::parse($tech['time_end']);
                $tech['time_end'] = $tech['time_end']->format('H:i:s');
            }

            $newArray[] = [
                'job_order_id' => $jobOrder->id,
                'technician_id' =>  $tech['addEmployees']['id'],
                'time_start' => $tech['time_start'],
                'time_end' => $tech['time_end']
            ];
        }

        return $jobOrder->technicians()->sync($newArray);
    }


    public function addMaterialsUsed(JobOrder $jobOrder, $materials)
    {
        $materials = collect($materials)->filter(function ($item) {
            return isset($item['stock_id']) && isset($item['quantity']);
        });

        foreach ($materials as $material) {
            $jobOrder->items()->create([
                'stock_id'      => $material['stock_id'],
                'qty'           => $material['quantity'],
            ]);

            //$this->stockService->moveOutById($material['stock_id'], $material['quantity'], $jobOrder);
        }

        return true;
    }

    /**
     * Approve Job Order
     *
     * @param JobOrder $jobOrder
     * @return bool
     */
    public function approve(JobOrder $jobOrder)
    {
        $jobOrder->status = JobOrder::APPROVED;

        if (! $jobOrder->time_end) {
            $jobOrder->status = JobOrder::PENDING;
        }

        if ($jobOrder->time_end) {
            $jobOrder->status = JobOrder::COMPLETED;
        }

        return $jobOrder->save();
    }

    /**
     * Approve Job Order
     *
     * @param JobOrder $jobOrder
     * @param $item_id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function dispatchItem(JobOrder $jobOrder, $item_id)
    {
        DB::beginTransaction();
        $item = $jobOrder->items()->findOrFail($item_id);

        // TODO: Let this be more elegant of a check that shows for the user in the UI.
        if ($item->qty > $item->stock->on_hand_quantity) {
            throw new Exception('Qty requested: '.$item->qty.' while there is only '.$item->stock->on_hand_quantity);
        }

        $item->update(['dispatched_at' => date('Y-m-d H:i:s')]);
        $this->stockService->moveOutById($item->stock_id, $item->qty, $jobOrder);
        DB::commit();

        return $item;
    }

    /**
     * Generate pdf
     *
     * @param JobOrder $jobOrder
     * @return \Illuminate\Http\Response
     */
    public function streamPdf(JobOrder $jobOrder)
    {
        $format = $jobOrder['job_order_number'].'.'.'pdf';

        $pdf = PDF::loadView('pdf.job-order.form', compact('jobOrder'));

        return $pdf->inline($format);
    }

    public function update(array $data)
    {
        $jo = DB::transaction(function() use ($data) {
            $jo = JobOrder::where('id', $data['jobOrder']['id'])
                ->firstOrFail();

            // Updating JO header.
            $jo->update([
                'date' => date('Y-m-d', strtotime($data['date'])),
                'employee_id' => array_key_exists('employee_id', $data) ? $data['employee_id'] : null,
                'cost_center_id' => array_key_exists('cost_center_id', $data) ? $data['cost_center_id'] : null,
                'ext' => $data['ext'],
                'requested_through_type' => $data['requested_through_type'],
                'job_description' => $data['job_description'],
                'remark' => $data['remark'],
                'location_id' => $data['location_id'],
                'time_start' => Carbon::parse($data['time_start']),
                'time_end' => $data['time_end'] ? Carbon::parse($data['time_end']) : '',
            ]);

            // Updating materials.
            // TODO: Updating materials.
            // Updating technicians.
            // TODO: Updating technicians.

            return $jo;
        });

        return $jo;
    }

    /**
     * Completes job order.
     *
     * @param $id
     * @return mixed
     */
    public function complete($id)
    {
        $jo = DB::transaction(function() use ($id) {
            $jo = JobOrder::where('id', $id)->firstOrFail();
            $jo->status = JobOrder::COMPLETED;
            $jo->save();

            // Close the time_end for all pending techs.
            $jo->technicians->each(function($tech) {
               if (!$tech->time_end) {
                   $tech->pivot->time_end = now();
                   $tech->pivot->save();
               }
            });

            return $jo;
        });

        return $jo;
    }
}
