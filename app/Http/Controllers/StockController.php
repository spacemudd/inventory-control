<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Stock;
use App\Services\StockService;
use Illuminate\Http\Request;
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
        $stocks = Stock::query();

        if (request()->has('sort-rack-desc')) {
            $stocks->orderBy('rack_number', 'desc');
        }

        $stocks->orderBy('description' , 'asc');

        $stocks = $stocks->get();

        return view('stock.index', compact('stocks'));
    }

    public function create()
    {
        return view('stock.create');
    }

    /**
     * Stores a new stock item and adjusts the quantity.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'        => 'nullable|string|max:255|unique:stock',
            'description' => 'required|unique:stock,description',
            'rack_number' => 'nullable|numeric|min:0',
            'available_quantity' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'recommended_qty' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        $stock = Stock::create($request->except('_token', 'available_quantity'));

        if ($request->available_quantity) {
            $this->service->addIn($stock->description, $request->available_quantity);
        }

        DB::commit();

        session()->flash('success', 'Added '.$stock->description.' (Quantity: '.$request->available_quantity.')');

        return redirect()->route('stock.index');
    }

    public function edit($id)
    {
        $stock = Stock::find($id);
        return view('stock.edit', compact('stock'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'nullable|string|min:3|unique:stock,code,'.$id,
            'description' => 'required|unique:stock,description,'.$id,
            'available_quantity' => 'required|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'rack_number' => 'nullable|numeric|min:0',
            'recommended_qty' => 'nullable|numeric|min:0',
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

        return redirect()->route('stock.index');
    }

    public function byCategory($category_id)
    {
        $stocks = Stock::where('category_id', $category_id)->get();
        $selectedCategory = Category::find($category_id);
            return view('stock.index', compact('stocks', 'selectedCategory'));
        }

    public function show($id)
    {
        $stock = Stock::find($id);
        return view('stock.show', compact('stock'));
    }
}
