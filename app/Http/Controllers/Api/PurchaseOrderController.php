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

use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Clarimount\Service\PurchaseOrderService;

class PurchaseOrderController extends Controller
{
    protected $service;

    public function __construct(PurchaseOrderService $service)
    {
    	$this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function paginatedIndex($per_page = 10)
    {
        return $this->service->paginatedIndex($per_page);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @see \App\Clarimount\Service\PurchaseOrderService@store
     */
    public function store()
    {
        return $this->service->store();
    }

    public function update($id)
    {
        $data = request()->except(['_token', '_method']);

        return $this->service->update($id, $data);
    }

    /**
     * @return mixed
     */
    public function search()
    {
        $search = request()->input('q');

        $purchaseOrders = PurchaseOrder::where('number', 'LIKE', '%' . $search . '%')
                                ->orWhere('id', 'LIKE', '%' . $search . '%');

        if($datePeriod = $this->parseDatePeriodDates()) {
            $purchaseOrders->whereBetween('date', $datePeriod);
        }

        //$purchaseOrders->orWhereHas('items', function($query) use ($search) {
        //    $query->where('manufacturer_serial_number', 'LIKE', '%' . $search . '%');
        //});

        $purchaseOrders->with('employee');

        return $purchaseOrders->paginate(15);
    }
    
    public function customSearch()
    {
    	$searchtype = request()->input('p');
    	$search = request()->input('q');
    	
    	$res = array();
    	if($searchtype=='po_number')
    	{
    		$res = PurchaseOrder::where('number', 'LIKE', '%' . $search . '%')
    				->with('location')
    				->with('vendor')
    				->get();
    		
    			//return $search;
    	}
    	
    	else if($searchtype=='vendor')
    	{
    		$resx = PurchaseOrder::query()
    		->join('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
    		->where('vendors.name', 'LIKE', '%' . $search . '%')
    		->select('purchase_orders.*')
    		->get();
    		
    		foreach ($resx as $row)
    		{
    			$res_row = PurchaseOrder::where('id', '=', $row->id)
    			->with('location')
    			->with('vendor')
    			->get();
    			
    			$res[] = (object)$res_row;
    			
    		}
    	}
    	
    	
    	else if($searchtype=='location')
    	{
    		$resx = PurchaseOrder::query()
    		->join('departments', 'purchase_orders.cost_center_id', '=', 'departments.id')
    		->where('departments.description', 'LIKE', '%' . $search . '%')
    		->select('purchase_orders.*')
    		->get();
    		
    		foreach ($resx as $row)
    		{
    			$res_row = PurchaseOrder::where('id', '=', $row->id)
    			->with('location')
    			->with('vendor')
    			->get();
    			
    			$res[] = (object)$res_row;
    			
    		}
    	}
    	
    	

    	return $res;
    }

    public function parseDatePeriodDates()
    {
        if(request()->has('datefrom') && request()->has('dateto')) {
            $datePeriod = [
                Carbon::parse(request()->input('datefrom'))->toDateTimeString(),
                Carbon::parse(request()->input('dateto'))->toDateTimeString(),
            ];

            return $datePeriod;
        }

        return;
    }

    public function show(Request $request)
    {
        return $this->service->show($request->only('id'));
    }

    /**
     * Commits a purchase order.
     *
     * @return bool
     */
    public function commit()
    {
        return $this->service->commit();
    }

    /**
     * Saves a purchase order.
     *
     * @return bool
     * @throws \Exception
     */
    public function save()
    {
        return $this->service->save();
    }

    /**
     * Voids a purchase order.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public function void(Request $request)
    {
        $po = PurchaseOrder::find($request->id);
        $po->status = PurchaseOrder::VOID;
        $po->save();

        if (request()->wantsJson()) {
            return $po;
        }

        return redirect()->route('purchase-orders.show', ['id' => $po->id]);
    }

    public function attachments()
    {
        return $this->service->attachments();
    }

    public function downloadAttachment()
    {
        return $this->service->downloadAttachment();
    }

    public function updateTokens($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'nullable|max:1000',
        ]);

        if($request->name === 'other_terms') {
            $po = PurchaseOrder::where('id', $id)->firstOrFail();
            $terms = $po->terms_json;
            $terms->Others = $request->value; // Add 'Others'.
            $po->terms_json = $terms;
            $po->save();

            return $po;
        }

        $data[$request->name] = $request->value;

        $po = PurchaseOrder::where('id', $id)
            ->with('requested_for_employee')
            ->with('requested_by_employee')
            ->with('cost_center')
            ->firstOrFail();

        $po->update($data);

        $this->service->updateHistoricalData($id);

        $po->refresh();

        return $po;
    }
    
   
}
