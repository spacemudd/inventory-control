<?php

namespace App\Http\Controllers;

use App\Classes\MaterialRequestExcel;
use App\Classes\MaterialRequestExcelAll;
use App\Models\Department;
use App\Models\Location;
use App\Models\MaterialRequest;
use App\Models\Region;
use App\Models\Stock;
use App\Services\MaterialRequestService;
use Illuminate\Http\Request;
use App\Models\MaterialRequestItem;
use Excel;
use Illuminate\Support\Facades\DB;
use PDF;

class MaterialRequestsController extends Controller
{
    protected $service;

    public function __construct(MaterialRequestService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mRequests = MaterialRequest::latest()->get();
        return view('material-requests.index', compact('mRequests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locations = Location::get();
        $departments = Department::get();
        $regions = Region::get();

        return view('material-requests.create', compact('locations', 'departments', 'regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'nullable|string|max:255|unique:material_requests,number',
            'date' => 'required|date',
            'location_id' => 'required|numeric|exists:locations,id',
            'cost_center_id' => 'required_without_all:department_code_number|numeric|exists:departments,id',
            'department_code_number' => 'required_without_all:cost_center_id|numeric',
            //'region_id' => 'required|numeric|exists:regions,id'
        ]);

        DB::beginTransaction();
        if(isset($request->department_code_number)):
            $locationName = Location::find($request->location_id);
            $id = Department::insertGetId([
                'code' => $request->department_code_number,
                'description' => $locationName->name,
                'location_id' => $request->location_id
            ]);
            $request['cost_center_id'] = $id;
        endif;
        $materialRequest = $this->service->save($request->except('_token'));
        DB::commit();

        return redirect()->route('material-requests.show', ['id' => $materialRequest->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mRequest = MaterialRequest::findOrFail($id);

        return view('material-requests.show', compact('mRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $locations = Location::get();
        $departments = Department::get();
        $regions = Region::get();
        $mRequest = MaterialRequest::find($id);

        return view('material-requests.edit', compact('locations', 'departments', 'regions', 'mRequest'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'number' => 'nullable|string|max:255|unique:material_requests,number,'.$id,
            'date' => 'required|date',
            'location_id' => 'required|numeric|exists:locations,id',
            'cost_center_id' => 'required_without_all:department_code_number|numeric|exists:departments,id',
            'department_code_number' => 'required_without_all:cost_center_id|numeric',
            //'region_id' => 'required|numeric|exists:regions,id'
        ]);

        DB::beginTransaction();
        if(isset($request->department_code_number)):
            $locationName = Location::find($request->location_id);
            $id = Department::insertGetId([
                'code' => $request->department_code_number,
                'description' => $locationName->name,
                'location_id' => $request->location_id
            ]);
            $request['cost_center_id'] = $id;
        endif;
        $materialRequest = $this->service->update($request->except('_token'), $id);
        DB::commit();

        return redirect()->route('material-requests.show', ['id' => $materialRequest->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function approve($id)
    {
        $mRequest = $this->service->approve($id);
        return redirect()->route('material-requests.show', ['id' => $mRequest->id]);
    }


    public function allExcel($type)
    {
        $x = new MaterialRequestExcel;
        $data = MaterialRequest::with('location', 'cost_center')->get();
        return $x->downloadMaterialRequests($data, $type);
    }

    /**
     * @param $itemName
     * @return false|string
     */
    public function searchItem($itemName)
    {

        $newItemName = trim($itemName);
        $itemInformation = MaterialRequestItem::where('description', 'like', '%' . $newItemName . '%')
            ->distinct()
            ->pluck('description');

        $s = Stock::where('description', 'like', '%'.$newItemName.'%')
            ->distinct()
            ->pluck('description');

        $itemInformation = $itemInformation->merge($s)->unique();

        return json_encode($itemInformation);
    }

    public function streamPdf($id)
    {
        $mRequest = MaterialRequest::with('items')->find($id);

        static $number = 1;

        $pdf = PDF::loadView('pdf.material-request.materialRequestExcel', compact('mRequest', 'number', 'id'));

        return $pdf->inline('material-request.pdf');
    }
}
