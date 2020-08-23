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
use App\Models\SupplierInvoice;

class SupplierInvoiceController extends Controller
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
	
	public function customSearch()
	{
		$search = request()->input('q');
		$res = array();
		
		$res = SupplierInvoice::where('number', 'LIKE', '%' . $search . '%')->get();
		
		
		return $res;
	}
	
}
