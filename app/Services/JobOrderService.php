<?php

namespace App\Services;

use PDF;
use Carbon\Carbon;
use App\Models\JobOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Models\Employee;

class JobOrderService
{
    /**
     * Create job order
     *
     * @param Request $request
     * @return JobOrder
     */
    public function save(Request $request)
    {
//        dd($request->employee_id);
        if($request->employee_id == null){
            $toInsert = [
                'code' => $request->employeeName,
                'department_id' => "null",
                'staff_type_id' => "null",
                'name' =>"null",
                'email' => "null",
                'phone' => "null",
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
            'quotation_id'
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
        return (1000) + optional(JobOrder::latest()->first())->id;
    }


    /**
     * Sync technicians to job order
     *
     * @param JobOrder $jobOrder
     * @param array $techinians
     * @return array
     */
    public function addTechniciansTo(JobOrder $jobOrder, $techinians)
    {
        $newArray = [];

        foreach ($techinians as &$tech) {
            unset($tech['employee']);

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

    /**
     * Approve Job Order
     *
     * @param JobOrder $jobOrder
     * @return bool
     */
    public function approve(JobOrder $jobOrder)
    {
        $jobOrder->status = JobOrder::APPROVED;

        return $jobOrder->save();
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
}
