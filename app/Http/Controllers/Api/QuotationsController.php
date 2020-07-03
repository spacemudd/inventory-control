<?php

namespace App\Http\Controllers\Api;

use App\Models\JobOrder;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuotationsController extends Controller
{
    public function search(Request $request)
    {
        $search = request()->input('q');
        return Quotation::where('vendor_quotation_number', 'LIKE', '%'.$search.'%')
            ->with('vendor')
            ->paginate(1000);
    }
}
