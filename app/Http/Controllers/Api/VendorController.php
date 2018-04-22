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

use App\Clarimount\Service\VendorService;
use App\Http\Controllers\Controller;
use App\Models\Vendor;

class VendorController extends Controller
{
    protected $service;

    public function __construct(VendorService $service)
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

    public function store()
    {
        $this->authorize('create-vendor');

        return $this->service->store();
    }

    /**
     * @return mixed
     */
    public function search()
    {
        $search = request()->input('q');

        $results = Vendor::where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('id', 'LIKE', '%' . $search . '%')
            ->paginate(10);

        return $results;
    }

}
