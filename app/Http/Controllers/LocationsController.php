<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    public function index()
    {
        $locations = Location::get();
        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string:255']);

        $loc = new Location();
        $loc->name = $request->name;
        $loc->save();

        return redirect()->route('locations.index');
    }
}
