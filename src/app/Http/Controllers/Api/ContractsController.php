<?php

namespace App\Http\Controllers\Api;

use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContractsController extends Controller
{
    public function search()
    {
        $search = request()->input('q');

        $contracts = Contract::where('number', 'LIKE', '%' . $search . '%')
            ->orWhereHas('vendor', function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })
            ->with('vendor');

        return $contracts->paginate(15);
    }
}
