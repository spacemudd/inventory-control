<?php

namespace App\Services;

use App\Models\MaterialRequestItem;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockService
{

    public function addOut(Request $request)
    {
        $quotation = Quotation::with('items')->findOrFail($request->quotation_id);

        foreach ($quotation->items as $item) {
            Stock::where('material_request_item_id', $item->material_request_item_id)->update(['out' =>  $item->qty]);
        }
    }

    /**
     * @param string $description
     * @param int $qty
     * @param \App\Models\QuotationItem $item
     * @return \App\Models\Stock
     */
    public function addIn(string $description, int $qty, QuotationItem $item=null): Stock
    {
        $stock = DB::transaction(function() use ($description, $qty, $item) {
            $stock = Stock::firstOrCreate([
                'description' => $description,
            ], [
                'description' => $description,
            ]);

            $stock->movement()->create([
                'stockable_id' => optional($item)->id,
                'stockable_type' => optional($item)->material_request_item_id,
                'in' => $qty,
                'out' => 0,
            ]);

            return $stock;

        });


        return $stock;
    }


}
