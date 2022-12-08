<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use Illuminate\Http\Request;
use App\Classes\MaterialRequestExcel;

class MaterialRequestItemsController extends Controller
{
    /**
     *
     * @param $materialRequestId
     * @return mixed
     */
    public function index($materialRequestId)
    {
        $request = MaterialRequest::where('id', $materialRequestId)
            ->with(['items' => function($q) {
                $q->with('last_template')
                ->with('last_quoted')
                ->with('stock_template');
            }])
            ->firstOrFail();

        return $request->items;
    }

    /**
     *
     * @param $id Material request ID.
     * @return mixed
     */
    public function makeExcel($id)
    {
        $type = 'xlsx';
        $materialRequest = MaterialRequest::find($id);
        $data = $materialRequest->items;
        $x = new MaterialRequestExcel($materialRequest);
        return $x->downloadMaterialRequestExcel($data, $type);
    }

    /**
     *
     * @param $id
     * @param \Illuminate\Http\Request $item
     * @return \App\Models\MaterialRequestItem
     */
    public function store($id, Request $item): MaterialRequestItem
    {
        $item->validate([
            'description' => 'required|string|max:255',
            'qty' => 'required|numeric|min:0',
        ]);

        $item = $item->except('_token');
        $item['material_request_id'] = $id;

        return MaterialRequestItem::create($item);
    }

    /**
     *
     * @param $materialRequestId
     * @param $id
     * @return mixed
     */
    public function destroy($materialRequestId, $id)
    {
        return MaterialRequestItem::where('id', $id)->delete();
    }
}
