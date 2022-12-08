<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Services\StockService;
use Illuminate\Http\Request;
use App\Classes\StockExcel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class StockController extends Controller
{
    protected $service;

    public function __construct(StockService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $stocks = Stock::query();
        $stocks->with('category');

        if (request()->has('sort_by')) {
            $stocks->orderBy(
                Str::before(request()->sort_by, '.'),
                Str::after(request()->sort_by, '.')
            );
        }

        return $stocks->paginate(request()->perPage);
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
            ->orWhere('code', 'LIKE', '%'.$search.'%')
            ->with('category')
            ->paginate(100);

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

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|unique:stock,code|string',
            'description' => 'nullable|string|unique:stock,description',
            'rack_number' => 'nullable|string',
            'recommended_qty' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        if ($stock = Stock::where('description', $request->description)->exists()) {
            // do nothing
            \Log::info('Assigning stock information to an already existing stock... ignoring.');
        } else {
            $stock = Stock::create([
                'code' => $request->code,
                'description' => $request->description,
                'rack_number' => $request->rack_number,
                'recommended_qty' => $request->recommended_qty,
                'category_id' => $request->category_id,
            ]);
            \Log::info('Storing new stock from quotation...');
            return $stock;
        };
    }

    public function bulkDelete(Request $request)
    {
        Schema::disableForeignKeyConstraints();

        $request->validate([
            'ids.*' => 'numeric|exists:stock,id',
        ]);
        Stock::whereIn('id', $request->ids)->delete();

        Schema::enableForeignKeyConstraints();

        return 'Success!';
    }
}
