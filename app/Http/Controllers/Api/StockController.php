<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Classes\StockExcel;

class StockController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->get('q');
        return Stock::where('description', 'like', "%{$term}%")
                        ->with('category')
                        ->paginate(20);
    }

    /**
     * Download an Excel file.
     *
     * @return mixed
     */
    public function exportExcel()
    {
        $obj = new StockExcel;
        return $obj->downloadStockExcel();
    }
}
