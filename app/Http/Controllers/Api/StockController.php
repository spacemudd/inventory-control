<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Services\StockService;
use Illuminate\Http\Request;
use App\Classes\StockExcel;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    protected $service;

    public function __construct(StockService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Stock::with('category')->get();
    }

    public function search(Request $request)
    {
        //
        //if ($term = $request->get('q')) {
        //    return Stock::where('description', 'like', "%{$term}%")
        //                    ->select('id', 'description')
        //                    ->get();
        //}
        //
        //return collect();
        //

        $search = request()->input('q');

        $stock = Stock::where('description', 'LIKE', '%' . $search . '%')
            ->with('category')
            ->paginate(20);

        return $stock;
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

    /**
     * Updates stock.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \App\Models\Stock
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'available_quantity' => 'required|min:0',
        ]);

        DB::beginTransaction();
        $stock = Stock::findOrFail($id);
        $stock->update($request->except('available_quantity'));
        if ($stock->on_hand_quantity != $request->available_quantity) {
            // Clear up the current quantity.
            $this->service->moveOut($stock->description, $stock->on_hand_quantity);
            // Add the new quantity.
            $this->service->addIn($stock->description, $request->available_quantity);
        }
        DB::commit();
        return $stock;
    }
}
