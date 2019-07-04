<?php

namespace App\Http\Controllers\Api;

use App\Models\JobOrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JobOrdersItemsController extends Controller
{
    public function destroy($id)
    {
        $item = JobOrderItem::find($id);

        if ($item->jobOrder->isCompleted()) {
            throw new \Exception('Cant delete item of a completed job order');
        }

        $item->delete();

        return [
            'msg' => 'success',
        ];
    }
}
