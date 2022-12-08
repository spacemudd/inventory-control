<?php

namespace App\Http\Controllers\Api;

use App\Models\Equipment;
use App\Models\EquipmentDisabled;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EquipmentsController extends Controller
{
    public function index()
    {
        return Equipment::where('type', 'equipment')->get();
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return
     */
    public function addNode(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pid' => 'nullable',
            'leaf' => 'boolean',
        ]);
        
        DB::unprepared('SET IDENTITY_INSERT inv_equipments ON');
        $type = ($request->leaf != False ? 'equipment' : 'location');
        if ($request->pid) {
            $parent = Equipment::where('id', (int)$request->pid)->first();
            $child =  new Equipment();
            $child->name = $request->name;
            $child->id = (int)$request->id;
            $child->parent_id = (int)$request->pid;
            $child->type = $type;
            $parent->children()->save($child);
        } else {
            Equipment::create([
                'id' => (int)$request->id,
                'name' => $request->name,
                'type' => $type,
            ]);
        }

        return Equipment::where('name', $request->name)->first();
    }

    public function changeNode(Request $request)
    {
        $request->validate([
            'new_name' => 'required|string|max:255',
            'id' => 'nullable',
        ]);

        $equip = Equipment::where('id', $request->id)->first();
        $equip->name = $request->new_name;
        $equip->save();

        return $equip;
    }

    public function dropNode(Request $request)
    {
        $request->validate([
            'pid' => 'nullable',
            'id' => 'required',
        ]);

        $equip = Equipment::where('id', $request->id)->first();
        $equip->parent_id = $request->pid;
        $equip->save();

        return $equip;
    }

    public function deleteNode($name)
    {
        Equipment::where('name', $name)->first()->delete();
        return 'Success';
    }

    public function toJsTree()
    {
        $eq = Equipment::get(['equipments.*'])->toTree()->toArray();
        
        if (request()->has('disabled')) {
            $eq = EquipmentDisabled::get(['equipments.*'])->toTree()->toArray();
        }

        return $eq;

    }
}
