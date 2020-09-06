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
            'parent' => 'nullable|string|max:255',
            'isLeaf' => 'nullable',
        ]);

        $category = $request->isLeaf ? 'category' : 'equipment';

        if ($request->parent) {
            $parent = Equipment::where('name', $request->parent)->first();
            $child = new Equipment();
            $child->name = $request->name;
            $child->category = $category;
            $parent->children()->save($child);
        } else {
            Equipment::create([
                'name' => $request->name,
                'category' => $category,
            ]);
        }

        return Equipment::where('name', $request->name)->first();
    }

    public function changeNode(Request $request)
    {
        $request->validate([
            'new_name' => 'required|string|max:255',
            'old_name' => 'required|string|max:255',
        ]);

        $equip = Equipment::where('name', $request->old_name)->first();
        $equip->name = $request->new_name;
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
        $eq = Equipment::get()->toTree()->toArray();

        if (request()->has('disabled')) {
            return $eq = EquipmentDisabled::get()->toTree()->toArray();
        }

        return $eq;

        $tree = [
            'id' => 1,
            'name' => 'Head Office',
            'dragDisabled' => false,
            'addTreeNodeDisabled' => false,
            'addLeafNodeDisabled' => false,
            'editNodeDisabled' => false,
            'delNodeDisabled' => false,
            'children' => [
                [
                    'name' => 'Chiller Equipment',
                    'children' => [
                        ['name' => 'H.O Chiller 1'],
                    ],
                ],
            ]
        ];

        return $tree;
        //
        //{
        //    name: 'Head Office',
        //    id: 1,
        //    pid: 0,
        //    dragDisabled: false,
        //    addTreeNodeDisabled: false,
        //    addLeafNodeDisabled: false,
        //    editNodeDisabled: false,
        //    delNodeDisabled: false,
        //    children: [
        //      {
        //        name: 'Chiller Equipment',
        //        id: 2,
        //        //isLeaf: true,
        //        pid: 1,
        //        children: [
        //          {
        //              name: 'H.O Chiller 1',
        //
        //          }
        //        ],
        //      }
        //    ]
        //  },
        //{
        //    name: 'Group 2',
        //    id: 3,
        //    pid: 0,
        //    disabled: false
        //  },
        //{
        //    name: 'Group 3',
        //    id: 4,
        //    pid: 0
        //  }
    }
}
