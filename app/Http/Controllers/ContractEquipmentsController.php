<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractEquipment;
use Illuminate\Http\Request;

class ContractEquipmentsController extends Controller
{
    public function create($id)
    {
        $contract = Contract::findOrFail($id);
        return view('contract-equipments.create', compact('contract'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'equipment_id' => 'required|exists:equipments,id',
            'location_id' => 'required|exists:locations,id',
        ]);

        ContractEquipment::create([
            'contract_id' => $request->contract_id,
            'equipment_id' => $request->equipment_id,
            'location_id' => $request->location_id,
        ]);

        return redirect()->route('contracts.show', $id);
    }
}
