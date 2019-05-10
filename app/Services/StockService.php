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

        $stock = DB::transaction(function () use ($request, $quotation) {
            foreach ($quotation->items as $item) {
                $stock = Stock::firstOrCreate([
                    'description' => $item->description,
                ]);

                $stock->movement()->create([
                    'stockable_id' => optional($item)->id,
                    'stockable_type' => optional($item)->material_request_item_id,
                    'in' => 0,
                    'out' => optional($item)->qty
                ]);
            }
            return $stock;
        });


        return $stock;
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
                'description' => $description
            ]);

            $stock->movement()->create([
                'stockable_id' => optional($item)->id,
                'stockable_type' => $item ? get_class($item) : null,
                'in' => $qty,
                'out' => 0,
            ]);

            return $stock;

        });


        return $stock;
    }


}
