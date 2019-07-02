<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobOrderPutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
        // TODO: Maybe we can check for permissions of the user
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //'region_id' => 'required|numeric|exists:regions,id',
            //'employeeName' => 'required_without_all:employee_id',
            'technicians' => 'required',
            'technicians.*.pivot.*.time_start' => 'nullable|required',
            'employee_id' => 'nullable|exists:employees,id',
            'cost_center_id' => 'nullable|exists:departments,id',
            'ext' => 'nullable',
            'requested_through_type' => 'required',
            'job_description' => 'required|max:255',
            'remark' => 'nullable',
            'date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'nullable',
            'location_id' => 'required|exists:locations,id',
            'materials'     => 'nullable',
            'materials.*.stock'  => 'required_with_all:materials.*.quantity',
            'materials.*.quantity'  => 'required_with_all:materials.*.stock_id',
        ];
    }
}
