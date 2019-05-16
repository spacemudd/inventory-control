<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function search(Request $request)
    {
        if ($term = $request->get('q')) {
            return Stock::where('description', 'like', "%{$term}%")
                            ->select('id', 'description')
                            ->get();
        }

        return collect();
    }
}
