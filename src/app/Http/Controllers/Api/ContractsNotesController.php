<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use App\Models\Contract;

class ContractsNotesController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:purchase_orders',
        ]);

        return Contract::findOrFail($request->id)->notes()->with('user')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:purchase_orders',
            'body' => 'required|string|max:255',
        ]);

        $note = new Note([
            'body' => $request->body,
            'user_id' => auth()->user()->id,
        ]);

        return Contract::findOrFail($request->id)
            ->notes()
            ->save($note)
            ->load('user');
    }
}
