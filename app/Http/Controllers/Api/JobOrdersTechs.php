<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use App\Models\JobOrder;
use App\Services\JobOrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JobOrdersTechs extends Controller
{
    protected $service;

    public function __construct(JobOrderService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        // todo: validation.
        $request->validate([
            'job_order_id' => 'required|exists:job_orders,id',
            'tech' => 'required',
        ]);

        $jo = JobOrder::find($request->job_order_id);
        $this->addTechToJo($jo, $request->tech);
        $jo = JobOrder::with(['items', 'technicians'])->findOrFail($request->job_order_id);
        return $jo;
    }

    /**
     * Used above.
     * TODO: move into service.
     *
     * @param \App\Models\JobOrder $jobOrder
     * @param array $tech
     * @return \App\Models\JobOrder|void
     */
    public function addTechToJo(JobOrder $jobOrder, array $tech)
    {
        if ($jobOrder->technicians()->where('id', $tech['addEmployees']['id'])->exists()) {
            // do nothing if tech already exists.
            return;
        }

        if ($tech['time_start']) {
            $tech['time_start'] = Carbon::parse($tech['time_start']);
            //$tech['time_start'] = $tech['time_start']->format('H:i:s');
        }
        if ($tech['time_end']) {
            $tech['time_end'] = Carbon::parse($tech['time_end']);
            //$tech['time_end'] = $tech['time_end']->format('H:i:s');
        }

        $newArray[] = [
            'job_order_id' => $jobOrder->id,
            'technician_id' =>  $tech['addEmployees']['id'],
            'time_start' => $tech['time_start'],
            'time_end' => $tech['time_end']
        ];

        $jobOrder->technicians()->attach($newArray);

        return $jobOrder;
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'job_order_id' => 'required|exists:job_orders,id',
            'tech' => 'required',
        ]);

        $jo = JobOrder::where('id', $request->job_order_id)->firstOrFail();
        $tech = Employee::find($request->tech['id']);
        $jo->technicians()->detach($tech);
        return $jo;
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function finish(Request $request)
    {
        $request->validate([
            'job_order_id' => 'required|exists:job_orders,id',
            'tech' => 'required',
        ]);

        $jo = JobOrder::where('id', $request->job_order_id)->firstOrFail();
        $tech = $jo->technicians()->where('id', $request->tech['id'])->first();

        if ($tech) {
            $jo->technicians()->updateExistingPivot($tech->id, [
                'time_end' => now(),
            ]);
        }

        return $jo;
    }
}
