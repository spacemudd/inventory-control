<?php
/**
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

namespace App\Http\Controllers\Api;

use App\Clarimount\Service\EmployeeService;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\StaffType;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
	protected $service;

	public function __construct(EmployeeService $service)
	{
		$this->service = $service;
	}

    public function index()
    {
        $this->authorize('view-employees');

        return $this->service->index();
    }

    /**
     * @return mixed
     */
    public function search()
    {
        $search = request()->input('q');

        $employees = Employee::where('code', 'LIKE', '%' . $search . '%')
            ->orWhere('name', 'LIKE', '%' . $search . '%')
            ->paginate(10);

        return $employees;
    }

    public function show(Request $request)
    {
        $this->authorize('view-employees');

        $request->validate([
            'id' => 'required|exists:employees,id'
        ]);

        $employee = Employee::where('id', $request->id)
            ->with('department')
            ->with('type')
            ->firstOrFail();

        return $employee;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create-employees');

        return $this->service->store();
    }

    /**
     * TODO: Remove this to be in its own controller.
     *
     * @return array
     */
    public function staffTypes()
    {
        $result['types'] = StaffType::get()->toArray();

        return $result;
    }

    public function allStaffTypes()
    {

        return StaffType::get();
    }

    public function getEmployees($employeeId)
    {
        return Employee::where('code', 'LIKE', "%$employeeId%")
            ->orWhere('name', 'LIKE', "%$employeeId%")
            ->get();
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return mixed
     */
    public function updateStaffType(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:staff_types,name,'.$id,
        ]);

        $type = StaffType::findOrFail($id);
        $type->name = $request->name;
        $type->save();

        return $type;
    }

    /**
     *
     * @param $id
     * @return array
     */
    public function deleteStaffType($id)
    {
        $type = StaffType::findOrFail($id);
        $type->delete();

        return [
            'msg' => 'success',
        ];
    }

    public function storeStaffType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:staff_types,name',
        ]);

        $type = StaffType::create($request->except('_token'));

        return $type;
    }
}
