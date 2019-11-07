<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentsController extends Controller
{
    public function index()
    {
        $totalCount = Equipment::get()->count();
        return view('equipments.index', compact('totalCount'));
    }
}
