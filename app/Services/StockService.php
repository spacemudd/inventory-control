<?php

namespace App\Services;

use App\Models\Quotation;
use App\Models\Stock;
use Illuminate\Http\Request;

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
     * @param $request
     */
    public function addIn($request)
    {
        $data['in'] = $request['qty'];
        $data['material_request_item_id'] = $request['material_request_item_id'];
        $data['out'] = 0;

        Stock::create($data);
    }


}
