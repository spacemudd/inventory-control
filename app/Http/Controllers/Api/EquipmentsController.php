<?php

namespace App\Http\Controllers\Api;

use App\Models\Equipment;
use App\Models\EquipmentDisabled;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        
        $type = ($request->leaf != False ? 'location' : 'equipment');

        if ($request->pid) {
            $parent = Equipment::where('id', (int)$request->pid)->first();
            $child =  new Equipment();
            $child->name = $request->name;
            $child->id = (int)$request->id;
            $child->is_leaf = $request->leaf;
            $child->parent_id = (int)$request->pid;
            $child->type = $type;
            $parent->children()->save($child);
        } else {
            Equipment::create([
                'id' => (int)$request->id,
                'name' => $request->name,
                'type' => $type,
                'is_leaf' => $request->leaf,
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
        $eq = Equipment::get(['is_leaf as isLeaf', 'equipments.*'])->toTree()->toArray();
        
        if (request()->has('disabled')) {
            return $eq = EquipmentDisabled::get()->toTree()->toArray();
        }

        return $eq;

    }
}
