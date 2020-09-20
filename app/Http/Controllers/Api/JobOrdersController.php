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
            'equipment',
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
        return JobOrder::where('job_order_number', 'LIKE', '%'.$search.'%')
            //->orWhere('job_description', 'LIKE', '%'.$search.'%')
            //->orWhereHas('items', function($q) use ($search) {
            //    $q->whereHas('stock', function($q) use ($search) {
            //        $q->where('description', 'LIKE', '%'.$search.'%');
            //    });
            //})->with(['items' => function($q) {$q->with('stock');}])
            ->with(['items' => function($q) {$q->with('stock');}])

            ->paginate(1000);
    }
    
    public function searchCustom(Request $request)
    {
    	$search = request()->input('q');
    	$searchType = request()->input('p');
    	
    	
    	
    	if($searchType=='employee')
    	{
    		 $jo = JobOrder::join('job_order_technician', 'job_order_technician.job_order_id', '=', 'job_orders.id')
				    		   ->join('employees', function($join) use($search) {
				    		   		$join->on('job_order_technician.technician_id', '=', 'employees.id')
				    		   		->where('employees.name', 'LIKE', '%'.$search.'%');
				    		   })
				    		   ->select('job_orders.*')
				    		   ->with(['items' => function($q) {$q->with('stock');}])
				    		   ->with('technicians') 
				    		   ->paginate(30);
				    		  
    		  
    		  return $jo;
    	}
    	
    	else if($searchType=='job_order_num')
    	{
    		return JobOrder::where('job_order_number', 'LIKE', '%'.$search.'%')
    		//->orWhere('job_description', 'LIKE', '%'.$search.'%')
    		//->orWhereHas('items', function($q) use ($search) {
    		//    $q->whereHas('stock', function($q) use ($search) {
    		//        $q->where('description', 'LIKE', '%'.$search.'%');
    		//    });
    		//})->with(['items' => function($q) {$q->with('stock');}])
    		->with(['items' => function($q) {$q->with('stock');}])
    		
    		->paginate(30);
    	}
    	
    	
    	else if($searchType=='materials')
    	{
    		return JobOrder::join('job_order_items', 'job_order_items.job_order_id', '=', 'job_orders.id')
    						->join('stock', 'job_order_items.stock_id', '=', 'stock.id')
    						
    						->select('job_orders.*')
    						->where('stock.description', 'LIKE', '%'.$search.'%')
    						->with(['items' => function($q) {$q->with('stock');}])
    		
    						->paginate(30);
    	}
    	
    	else if($searchType=='equipment')
    	{
    		return JobOrder::join('equipments', 'equipments.id', '=', 'job_orders.equipment_id')
    		->select('job_orders.*')
    		->where('equipments.name', 'LIKE', '%'.$search.'%')
    		->with(['items' => function($q) {$q->with('stock');}])
    		
    		->paginate(30);
    	}
    	
    	
    	
    	return [];
    	
    }
    
    
}
