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

namespace App\Http\Controllers;

use App\Clarimount\Service\InventoryPurchaseOrderService;
use App\Clarimount\Service\PurchaseOrderService;
use App\Clarimount\Service\VendorBankService;
use App\Model\PurchaseTermsType;
use App\Models\Address;
use App\Models\PurchaseOrder;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Vendor;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\PurchaseOrderQuotation;
use DB;

class PurchaseOrderController extends Controller
{

    protected $service;

    protected $vendorBankService;

    protected $inventoryPoService;

    protected $casts = [
        'created_at'  => 'date:d-m-Y',
    ];

    public function __construct(PurchaseOrderService $service, VendorBankService $vendorBankService, InventoryPurchaseOrderService $inventoryPoService)
    {
        $this->service = $service;
        $this->vendorBankService = $vendorBankService;
        $this->inventoryPoService = $inventoryPoService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view-purchase-orders');

      //  $draftCounter = PurchaseOrder::draft()->count(); --commented this to minimize memory allocation/usage.
        $committedCounter = PurchaseOrder::committed()->count();
        $voidCounter = PurchaseOrder::void()->count();
		
        $data = PurchaseOrder::latest()->paginate(100);

        return view('purchase-orders.index', compact('committedCounter', 'voidCounter', 'data'));
    }

    public function draft()
    {
        $this->authorize('view-purchase-orders');

        $purchaseOrders = PurchaseOrder::draft()->latest()->paginate(50);
        return view('purchase-orders.draft', compact('purchaseOrders'));
    }

    public function committed()
    {
        $this->authorize('view-purchase-orders');

        $data = PurchaseOrder::committed()->latest()->paginate(50);
        return view('purchase-orders.committed', compact('data'));
    }

    public function void()
    {
        $this->authorize('view-purchase-orders');

        $data = PurchaseOrder::void()->latest()->paginate(50);
        return view('purchase-orders.void', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create-purchase-orders');

        $shipping_addresses = Address::shipping()->get();
        $billing_addresses = Address::billing()->get();

        $vendors = Vendor::get();

        $currencies = $this->vendorBankService->currencies();

        $quotes = Quotation::savedQuotations()->get();

        return view('purchase-orders.create', compact('shipping_addresses', 'billing_addresses', 'vendors', 'currencies', 'quotes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @see  \App\Clarimount\Service\PurchaseOrderService@store
     */
    public function store(Request $request)
    {
        $this->authorize('create-purchase-orders');
	
        DB::beginTransaction();
        $po = $this->inventoryPoService->store($request);
        
        foreach ($request->quotation_numbers as $number) {
        	$po->adhoc_quotations()->save(new PurchaseOrderQuotation(['quotation_number' => $number]));
        }
        
        DB::commit();

        return redirect()->route('purchase-orders.show', ['id' => $po->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        //$this->authorize('view-purchase-orders');
		/*
		 *  Enabled other user roles temporarily to allow other users access this page
		 */
    	
        $purchase_order = $this->service->show($id);

        if($purchase_order->purchase_order_main_id) return abort(404);

        $purchaseTermsTypes = PurchaseTermsType::get();

        return view('purchase-orders.show', compact('purchase_order', 'purchaseTermsTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $this->authorize('update-purchase-orders');

        $purchase_order = $this->service->edit($id);

        //$vendors = Vendor::active()->get();
        //$departments = Department::active()->get();
        //$employees = Employee::active()->get();

        return view('purchase-orders.edit', compact('purchase_order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update($id)
    {
        $this->authorize('update-purchase-orders');

        $data = request()->except(['_token', '_method']);
        $successful = $this->service->update($id, $data);

        if($successful) {
            session()->flash('status', 'success');
            session()->flash('message', trans('statements.successfully-saved'));
            return redirect()->route('purchase-orders.show', ['id' => $id]);
        } else {
            session()->flash('status', 'danger');
            session()->flash('message', trans('words.error-occurred'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $this->authorize('delete-purchase-orders');

        $result = $this->service->destroy($id);

        if($result) {
            session()->flash('status', 'success');
            session()->flash('message', trans('statements.successfully-deleted'));

            return redirect()->route('purchase-orders.index');
        } else {
            session()->flash('status', 'danger');
            session()->flash('message', trans('words.error-occurred'));
            return redirect()->back();
        }
    }

    /**
     *
     * @param $id po id
     * @return mixed
     * @throws \Exception
     */
    public function save($id)
    {
        // todo: make this api/json friendly.
        if(!$this->service->isReadyToSave($id)) {
            session()->flash('status', 'is-warning');
            session()->flash('messages', ['Purchase order has missing tokens. Fields must be completed first.']);
            return redirect()->back();
        }

        if(!$this->service->show($id)->lines()->count()) {
            session()->flash('status', 'is-warning');
            session()->flash('messages', ['There are no items attached.']);
            return redirect()->back();
        }

        $po = $this->service->save($id);

        return redirect()->route('purchase-orders.show', ['id' => $po->id]);
    }

    /**
     * Print a PDF form of the PO.
     *
     * @param $id
     * @return \Knp\Snappy\Pdf
     * @throws \Exception
     */
    public function pdf($id)
    {
        $pdf = $this->service->pdf($id);

        return $pdf->inline();
    }
}
