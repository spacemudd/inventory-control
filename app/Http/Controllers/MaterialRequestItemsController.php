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
        return MaterialRequest::where('id', $materialRequestId)
            ->firstOrFail()
            ->items;
    }

    public function makeExcel($id)
    {

        $type = 'xlsx';
        $x = new MaterialRequestExcel;
        $data = MaterialRequestItem::where('material_request_id', $id)->get();
        $x->downloadMaterialRequestExcel($data, $type);
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
            'qty' => 'required|numeric|min:1',
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
